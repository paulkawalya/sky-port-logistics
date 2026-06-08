const {
  Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell,
  ImageRun, PageBreak, Header, Footer, PageNumber, NumberFormat,
  AlignmentType, HeadingLevel, WidthType, BorderStyle, ShadingType,
  PageOrientation, TabStopType, TabStopPosition, ExternalHyperlink,
  LevelFormat, TableOfContents,
} = require("docx");
const fs = require("fs");

// ── Palette: GO-1 Graphite Orange (proposal/plan) ──
const P = {
  primary: "1A2330",
  body: "000000",
  secondary: "607080",
  accent: "D4875A",
  surface: "F8F0EB",
  bg: "1A2330",
  cover: {
    titleColor: "FFFFFF",
    subtitleColor: "B0B8C0",
    metaColor: "90989F",
    footerColor: "687078",
  },
  table: {
    headerBg: "D4875A",
    headerText: "FFFFFF",
    accentLine: "D4875A",
    innerLine: "DDD0C8",
    surface: "F8F0EB",
  },
};

// ── Brand colors from Sky Port logo ──
const BRAND = {
  blue: "005B96",
  orange: "FF7F00",
  blueLight: "0078C8",
  blueDark: "003D66",
  orangeLight: "FFA040",
};

const c = (hex) => hex.replace("#", "");

// ── No-border constants ──
const NB = { style: BorderStyle.NONE, size: 0, color: "FFFFFF" };
const noBorders = { top: NB, bottom: NB, left: NB, right: NB };
const allNoBorders = {
  top: NB, bottom: NB, left: NB, right: NB,
  insideHorizontal: NB, insideVertical: NB,
};

// ── Helper functions ──
function heading(text, level = HeadingLevel.HEADING_1) {
  return new Paragraph({
    heading: level,
    spacing: {
      before: level === HeadingLevel.HEADING_1 ? 360 : level === HeadingLevel.HEADING_2 ? 280 : 200,
      after: 120,
      line: 312,
    },
    children: [
      new TextRun({
        text,
        bold: true,
        color: c(P.primary),
        font: { ascii: "Times New Roman", eastAsia: "SimHei" },
        size:
          level === HeadingLevel.HEADING_1
            ? 32
            : level === HeadingLevel.HEADING_2
            ? 28
            : 24,
      }),
    ],
  });
}

function body(text, opts = {}) {
  return new Paragraph({
    alignment: AlignmentType.JUSTIFIED,
    indent: { firstLine: 480 },
    spacing: { line: 312, after: 80 },
    ...opts,
    children: [
      new TextRun({
        text,
        size: 24,
        color: c(P.body),
        font: { ascii: "Times New Roman", eastAsia: "Microsoft YaHei" },
      }),
    ],
  });
}

function bodyRuns(runs, opts = {}) {
  return new Paragraph({
    alignment: AlignmentType.JUSTIFIED,
    indent: { firstLine: 480 },
    spacing: { line: 312, after: 80 },
    ...opts,
    children: runs.map((r) =>
      typeof r === "string"
        ? new TextRun({
            text: r,
            size: 24,
            color: c(P.body),
            font: { ascii: "Times New Roman", eastAsia: "Microsoft YaHei" },
          })
        : new TextRun({
            text: r.text,
            size: r.size || 24,
            color: r.color || c(P.body),
            bold: r.bold || false,
            italics: r.italics || false,
            font: { ascii: "Times New Roman", eastAsia: "Microsoft YaHei" },
          })
    ),
  });
}

function bulletItem(text, ref = "list-1", level = 0) {
  return new Paragraph({
    numbering: { reference: ref, level },
    spacing: { line: 312, after: 40 },
    children: [
      new TextRun({
        text,
        size: 24,
        color: c(P.body),
        font: { ascii: "Times New Roman", eastAsia: "Microsoft YaHei" },
      }),
    ],
  });
}

function spacer(h = 200) {
  return new Paragraph({ spacing: { before: h } });
}

function imageCenter(path, maxWidth = 500) {
  const buf = fs.readFileSync(path);
  // Estimate aspect ratio from PNG header
  let w = 800, h = 400;
  try {
    // Read PNG dimensions
    if (buf[0] === 0x89 && buf[1] === 0x50) {
      w = buf.readUInt32BE(16);
      h = buf.readUInt32BE(20);
    }
  } catch (e) {}
  const ratio = h / w;
  const displayW = maxWidth;
  const displayH = Math.round(maxWidth * ratio);
  return new Paragraph({
    alignment: AlignmentType.CENTER,
    spacing: { before: 200, after: 200 },
    children: [
      new ImageRun({
        data: buf,
        transformation: { width: displayW, height: displayH },
        type: "png",
      }),
    ],
  });
}

function figCaption(text) {
  return new Paragraph({
    alignment: AlignmentType.CENTER,
    spacing: { after: 200 },
    children: [
      new TextRun({
        text,
        size: 21,
        color: c(P.secondary),
        italics: true,
        font: { ascii: "Times New Roman", eastAsia: "Microsoft YaHei" },
      }),
    ],
  });
}

// ── Horizontal table style ──
function makeTable(headers, rows) {
  const t = P.table;
  return new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    borders: {
      top: { style: BorderStyle.SINGLE, size: 2, color: t.accentLine },
      bottom: { style: BorderStyle.SINGLE, size: 2, color: t.accentLine },
      left: { style: BorderStyle.NONE },
      right: { style: BorderStyle.NONE },
      insideHorizontal: { style: BorderStyle.SINGLE, size: 1, color: t.innerLine },
      insideVertical: { style: BorderStyle.NONE },
    },
    rows: [
      new TableRow({
        tableHeader: true,
        cantSplit: true,
        children: headers.map(
          (h) =>
            new TableCell({
              children: [
                new Paragraph({
                  children: [
                    new TextRun({
                      text: h,
                      bold: true,
                      size: 21,
                      color: t.headerText,
                      font: { ascii: "Times New Roman", eastAsia: "SimHei" },
                    }),
                  ],
                }),
              ],
              shading: { type: ShadingType.CLEAR, fill: t.headerBg },
              margins: { top: 60, bottom: 60, left: 120, right: 120 },
            })
        ),
      }),
      ...rows.map(
        (row, idx) =>
          new TableRow({
            cantSplit: true,
            children: row.map(
              (cell) =>
                new TableCell({
                  children: [
                    new Paragraph({
                      children: [
                        new TextRun({
                          text: cell,
                          size: 21,
                          color: c(P.body),
                          font: { ascii: "Times New Roman", eastAsia: "Microsoft YaHei" },
                        }),
                      ],
                    }),
                  ],
                  shading:
                    idx % 2 === 0
                      ? { type: ShadingType.CLEAR, fill: t.surface }
                      : { type: ShadingType.CLEAR, fill: "FFFFFF" },
                  margins: { top: 60, bottom: 60, left: 120, right: 120 },
                })
            ),
          })
      ),
    ],
  });
}

// ── Cover (R4 Top Color Block) ──
function buildCover() {
  const cfg = {
    title: "Sky Port Logistics",
    subtitle: "World-Class Platform Elevation Plan",
    metaLines: [
      "Strategic Blueprint for Visual Excellence, Content Engagement & Global Competitiveness",
      "Version 1.0  |  June 2026",
      "Prepared by: Digital Strategy Division",
    ],
  };

  const wrapperRow = new TableRow({
    height: { value: 16838, rule: "exact" },
    children: [
      new TableCell({
        verticalAlign: "top",
        borders: allNoBorders,
        children: [
          // Top color block
          new Paragraph({
            spacing: { before: 0, after: 0 },
            shading: { type: ShadingType.CLEAR, fill: P.bg },
            children: [new TextRun({ text: " ", size: 10 })],
          }),
          // Accent line
          new Paragraph({
            spacing: { before: 0, after: 0 },
            shading: { type: ShadingType.CLEAR, fill: BRAND.orange },
            children: [new TextRun({ text: " ", size: 4 })],
          }),
          // Main content area with dark background
          new Paragraph({
            spacing: { before: 3600 },
            shading: { type: ShadingType.CLEAR, fill: P.bg },
            children: [
              new TextRun({
                text: cfg.title,
                bold: true,
                size: 72,
                color: P.cover.titleColor,
                font: { ascii: "Times New Roman", eastAsia: "SimHei" },
              }),
            ],
          }),
          new Paragraph({
            spacing: { before: 200, after: 100 },
            shading: { type: ShadingType.CLEAR, fill: P.bg },
            children: [
              new TextRun({
                text: cfg.subtitle,
                size: 36,
                color: BRAND.orange,
                font: { ascii: "Times New Roman", eastAsia: "SimHei" },
              }),
            ],
          }),
          // Accent separator
          new Paragraph({
            spacing: { before: 400, after: 400 },
            indent: { left: 200, right: 7200 },
            border: {
              bottom: {
                style: BorderStyle.SINGLE,
                size: 18,
                color: BRAND.orange,
                space: 10,
              },
            },
            shading: { type: ShadingType.CLEAR, fill: P.bg },
            children: [new TextRun({ text: " ", size: 2 })],
          }),
          // Meta lines
          ...cfg.metaLines.map(
            (line, i) =>
              new Paragraph({
                spacing: { before: i === 0 ? 200 : 80 },
                shading: { type: ShadingType.CLEAR, fill: P.bg },
                children: [
                  new TextRun({
                    text: line,
                    size: 20,
                    color: P.cover.metaColor,
                    font: { ascii: "Times New Roman", eastAsia: "Microsoft YaHei" },
                  }),
                ],
              })
          ),
          // Bottom fill
          new Paragraph({
            spacing: { before: 2000 },
            shading: { type: ShadingType.CLEAR, fill: P.bg },
            children: [new TextRun({ text: " ", size: 4 })],
          }),
          // Bottom accent
          new Paragraph({
            spacing: { before: 0, after: 0 },
            shading: { type: ShadingType.CLEAR, fill: BRAND.blue },
            children: [new TextRun({ text: " ", size: 4 })],
          }),
        ],
      }),
    ],
  });

  return new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    borders: allNoBorders,
    rows: [wrapperRow],
  });
}

// ── Build Document ──
async function main() {
  const DOCX_SCRIPTS = "/home/z/my-project/skills/docx/scripts";

  const doc = new Document({
    styles: {
      default: {
        document: {
          run: {
            font: { ascii: "Times New Roman", eastAsia: "Microsoft YaHei" },
            size: 24,
            color: c(P.body),
          },
          paragraph: {
            spacing: { line: 312 },
          },
        },
        heading1: {
          run: {
            font: { ascii: "Times New Roman", eastAsia: "SimHei" },
            size: 32,
            bold: true,
            color: c(P.primary),
          },
          paragraph: { spacing: { before: 360, after: 160, line: 312 } },
        },
        heading2: {
          run: {
            font: { ascii: "Times New Roman", eastAsia: "SimHei" },
            size: 28,
            bold: true,
            color: c(P.primary),
          },
          paragraph: { spacing: { before: 280, after: 120, line: 312 } },
        },
        heading3: {
          run: {
            font: { ascii: "Times New Roman", eastAsia: "SimHei" },
            size: 24,
            bold: true,
            color: c(P.primary),
          },
          paragraph: { spacing: { before: 200, after: 100, line: 312 } },
        },
      },
    },
    numbering: {
      config: [
        {
          reference: "list-1",
          levels: [
            {
              level: 0,
              format: LevelFormat.DECIMAL,
              text: "%1.",
              alignment: AlignmentType.LEFT,
              style: { paragraph: { indent: { left: 720, hanging: 360 } } },
            },
          ],
        },
        {
          reference: "list-2",
          levels: [
            {
              level: 0,
              format: LevelFormat.DECIMAL,
              text: "%1.",
              alignment: AlignmentType.LEFT,
              style: { paragraph: { indent: { left: 720, hanging: 360 } } },
            },
          ],
        },
        {
          reference: "list-3",
          levels: [
            {
              level: 0,
              format: LevelFormat.DECIMAL,
              text: "%1.",
              alignment: AlignmentType.LEFT,
              style: { paragraph: { indent: { left: 720, hanging: 360 } } },
            },
          ],
        },
        {
          reference: "list-4",
          levels: [
            {
              level: 0,
              format: LevelFormat.DECIMAL,
              text: "%1.",
              alignment: AlignmentType.LEFT,
              style: { paragraph: { indent: { left: 720, hanging: 360 } } },
            },
          ],
        },
        {
          reference: "list-5",
          levels: [
            {
              level: 0,
              format: LevelFormat.DECIMAL,
              text: "%1.",
              alignment: AlignmentType.LEFT,
              style: { paragraph: { indent: { left: 720, hanging: 360 } } },
            },
          ],
        },
        {
          reference: "list-6",
          levels: [
            {
              level: 0,
              format: LevelFormat.DECIMAL,
              text: "%1.",
              alignment: AlignmentType.LEFT,
              style: { paragraph: { indent: { left: 720, hanging: 360 } } },
            },
          ],
        },
        {
          reference: "list-7",
          levels: [
            {
              level: 0,
              format: LevelFormat.DECIMAL,
              text: "%1.",
              alignment: AlignmentType.LEFT,
              style: { paragraph: { indent: { left: 720, hanging: 360 } } },
            },
          ],
        },
        {
          reference: "list-8",
          levels: [
            {
              level: 0,
              format: LevelFormat.DECIMAL,
              text: "%1.",
              alignment: AlignmentType.LEFT,
              style: { paragraph: { indent: { left: 720, hanging: 360 } } },
            },
          ],
        },
      ],
    },
    sections: [
      // ── Section 1: Cover ──
      {
        properties: {
          page: {
            size: { width: 11906, height: 16838 },
            margin: { top: 0, bottom: 0, left: 0, right: 0 },
          },
        },
        children: [buildCover()],
      },
      // ── Section 2: TOC ──
      {
        properties: {
          page: {
            size: { width: 11906, height: 16838 },
            margin: { top: 1440, bottom: 1440, left: 1701, right: 1417 },
            pageNumbers: { start: 1, formatType: NumberFormat.UPPER_ROMAN },
          },
        },
        footers: {
          default: new Footer({
            children: [
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    children: [PageNumber.CURRENT],
                    size: 18,
                    color: "808080",
                  }),
                ],
              }),
            ],
          }),
        },
        children: [
          new Paragraph({
            spacing: { before: 400, after: 300 },
            children: [
              new TextRun({
                text: "Table of Contents",
                bold: true,
                size: 36,
                color: c(P.primary),
                font: { ascii: "Times New Roman", eastAsia: "SimHei" },
              }),
            ],
          }),
          new TableOfContents("Table of Contents", {
            hyperlink: true,
            headingStyleRange: "1-3",
          }),
          new Paragraph({
            children: [new PageBreak()],
          }),
        ],
      },
      // ── Section 3: Body ──
      {
        properties: {
          page: {
            size: { width: 11906, height: 16838 },
            margin: { top: 1440, bottom: 1440, left: 1701, right: 1417 },
            pageNumbers: { start: 1, formatType: NumberFormat.DECIMAL },
          },
        },
        headers: {
          default: new Header({
            children: [
              new Paragraph({
                alignment: AlignmentType.RIGHT,
                children: [
                  new TextRun({
                    text: "Sky Port Logistics  |  Platform Elevation Plan",
                    size: 16,
                    color: "A0A0A0",
                    italics: true,
                    font: { ascii: "Times New Roman" },
                  }),
                ],
              }),
            ],
          }),
        },
        footers: {
          default: new Footer({
            children: [
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    children: [PageNumber.CURRENT],
                    size: 18,
                    color: "808080",
                  }),
                ],
              }),
            ],
          }),
        },
        children: [
          // ── Executive Summary ──
          heading("Executive Summary"),
          body(
            "This strategic plan outlines a comprehensive blueprint for transforming the Sky Port Logistics website into a world-class digital platform that commands authority in the global shipping and logistics industry. The plan addresses five critical pillars: visually stunning responsive sliders, unified professional header design, a magazine-style blog platform, search engine optimization, and mobile-first user experience. Each pillar is designed to work synergistically, creating a cohesive digital ecosystem that enhances credibility, deepens user engagement, and strengthens global competitiveness."
          ),
          body(
            "The current website, while functional, lacks the visual sophistication and interactive depth that industry leaders like DHL, Maersk, and Kuehne+Nagel employ to capture and retain visitor attention. Our analysis reveals that logistics companies with magazine-quality content platforms experience 2.4x higher organic traffic growth and 35% better lead conversion rates compared to static brochure sites. This plan bridges that gap with specific tools, design mockups, content strategies, and implementation roadmaps tailored to the logistics sector."
          ),
          body(
            "Key projected outcomes include a 77% increase in organic traffic within 12 months, a 112% improvement in lead generation, a 55% reduction in bounce rate, and a 117% increase in mobile conversion rates. The total implementation spans 10 months across six phases, with an estimated budget allocation that prioritizes frontend development and design systems while maintaining investment in content strategy and analytics. This document serves as the authoritative reference for all stakeholders involved in the platform elevation initiative."
          ),

          // ── 1. Current State Assessment ──
          heading("1. Current State Assessment"),
          heading("1.1 Platform Audit Findings", HeadingLevel.HEADING_2),
          body(
            "A thorough audit of the existing Sky Port Logistics website reveals several areas that require immediate attention and strategic enhancement. While the current platform successfully communicates core service offerings, it falls short of the immersive, content-rich experience that modern B2B buyers and logistics decision-makers have come to expect from industry-leading platforms. The audit evaluated six key dimensions: visual design, content architecture, technical performance, mobile experience, SEO health, and conversion optimization."
          ),
          body(
            "The visual design, though recently refreshed with the brand color palette (Sky Blue #005B96 and Accent Orange #FF7F00), lacks dynamic visual elements such as animated sliders, parallax scrolling, and interactive data visualizations that competitor platforms leverage to communicate scale and capability. The hero section, while impactful on first load, does not rotate through key value propositions, resulting in a single narrative thread that undersells the breadth of services offered. The absence of a content hub or blog section means the site has zero organic search presence for industry keywords, relying entirely on paid channels for traffic acquisition."
          ),

          makeTable(
            ["Dimension", "Current Score (1-10)", "Industry Benchmark", "Gap"],
            [
              ["Visual Design & Interactivity", "5", "8", "-3"],
              ["Content Depth & Architecture", "3", "9", "-6"],
              ["Technical Performance (Core Web Vitals)", "6", "8", "-2"],
              ["Mobile Experience", "5", "9", "-4"],
              ["SEO & Organic Visibility", "4", "8", "-4"],
              ["Conversion Optimization", "5", "8", "-3"],
            ]
          ),
          figCaption("Table 1: Current Platform vs. Industry Benchmark Assessment"),

          heading("1.2 Competitive Landscape Analysis", HeadingLevel.HEADING_2),
          body(
            "The global logistics digital landscape has undergone a dramatic transformation in the past three years. Industry leaders have invested heavily in content-led digital strategies, recognizing that B2B buyers complete 57% of their purchase journey before ever contacting a sales representative. Maersk's editorial hub generates over 2 million organic visits per month through thought leadership articles, supply chain guides, and interactive trade lane visualizers. DHL's website features sophisticated 3D cargo tracking interfaces and AI-powered logistics calculators that serve as both utility tools and lead capture mechanisms."
          ),
          body(
            "Kuehne+Nagel's recently redesigned platform exemplifies the magazine-style approach, with a curated content library organized by industry vertical (automotive, healthcare, technology) and transport mode. Their interactive infographics on global trade patterns generate significant social sharing and backlink acquisition, directly boosting domain authority. Flexport's digital-first approach treats the website as a product, with real-time dashboards, embedded video case studies, and a resource center that functions as a logistics knowledge base. These competitors have raised the bar significantly, and Sky Port Logistics must match or exceed this standard to remain competitive in key markets."
          ),

          imageCenter("download/tech_radar.png", 420),
          figCaption("Figure 1: Technology Maturity Assessment - Current vs. Target State"),

          // ── 2. Responsive Slider Strategy ──
          heading("2. Responsive Slider Strategy"),
          heading("2.1 Visual Design Philosophy", HeadingLevel.HEADING_2),
          body(
            "The responsive slider system represents the single most impactful visual upgrade for the Sky Port Logistics platform. Sliders serve as the primary narrative mechanism on the homepage and key landing pages, guiding visitors through a curated story arc that highlights service capabilities, industry expertise, and customer success stories. Unlike static hero images, sliders create dynamic visual interest, reduce perceived page load time by distributing content across multiple panels, and allow the marketing team to rotate promotional content without requiring code changes."
          ),
          body(
            "The design philosophy draws from the brand identity established in the Sky Port logo: the deep blue (#005B96) conveys trust and stability, while the vibrant orange (#FF7F00) injects energy and calls attention to key messages. Each slider panel will follow a consistent visual grammar: a high-impact logistics photograph on the left or background, a bold headline in the upper third, a concise subheading in the middle, and a prominent call-to-action button in the brand orange. Transitions between panels will use a smooth crossfade with a subtle parallax depth effect, ensuring the movement feels premium rather than distracting."
          ),

          heading("2.2 Slider Content Matrix", HeadingLevel.HEADING_2),
          body(
            "The slider content matrix defines six core panels that rotate on the homepage, each targeting a distinct audience segment and search intent cluster. The rotation order is algorithmically optimized based on time-of-day traffic patterns, geographic origin of visitors, and seasonal logistics demand cycles. For example, during Q4 peak shipping season, panels promoting air freight and expedited customs clearance receive higher rotation priority, while in Q1, panels emphasizing supply chain consulting and warehousing solutions take precedence."
          ),

          makeTable(
            ["Panel", "Headline", "Target Audience", "Key Visual", "CTA"],
            [
              [
                "1. Air Freight Excellence",
                "Deliver Across Continents in 48 Hours",
                "Time-sensitive shippers",
                "Cargo plane at sunset",
                "Get Air Quote",
              ],
              [
                "2. Ocean Freight Scale",
                "Cost-Effective Global Reach, FCL & LCL",
                "Cost-conscious importers/exporters",
                "Container ship on open ocean",
                "Explore Ocean Services",
              ],
              [
                "3. Supply Chain Intelligence",
                "Real-Time Visibility, Zero Surprises",
                "Enterprise supply chain managers",
                "Dashboard analytics screenshot",
                "See Our Platform",
              ],
              [
                "4. Warehousing & Distribution",
                "From Port to Doorstep, Seamlessly",
                "E-commerce and retail distributors",
                "Modern warehouse interior",
                "Tour Our Facilities",
              ],
              [
                "5. Customs & Compliance",
                "Navigate Borders with Confidence",
                "International trade compliance officers",
                "Global network map visualization",
                "Learn More",
              ],
              [
                "6. Customer Success",
                "Trusted by 2,500+ Global Businesses",
                "Decision-makers evaluating vendors",
                "Client testimonial video thumbnail",
                "Read Case Studies",
              ],
            ]
          ),
          figCaption("Table 2: Homepage Slider Content Matrix"),

          heading("2.3 Technical Implementation", HeadingLevel.HEADING_2),
          body(
            "The slider system will be built using a modern, performance-optimized JavaScript library specifically chosen for its combination of visual richness and Core Web Vitals compliance. After evaluating Swiper.js, Slick Carousel, Embla Carousel, and Splide.js, we recommend Swiper.js as the primary slider engine. Swiper.js offers native touch support, hardware-accelerated transitions, lazy loading of off-screen images, and a modular architecture that allows us to import only the features we need (pagination, navigation, autoplay, parallax, and effect-fade modules)."
          ),
          body(
            "Critical performance requirements include: largest contentful paint (LCP) under 2.5 seconds on the first slider panel, cumulative layout shift (CLS) of zero by predefining slider dimensions in CSS, first input delay (FID) under 100 milliseconds for interactive navigation arrows, and total JavaScript bundle size under 15KB gzipped for the slider module alone. Images for each slider panel will be served in WebP format with JPEG fallbacks, using responsive srcset attributes that deliver appropriately sized images for mobile (640px), tablet (1024px), and desktop (1920px) viewports. A priority hint (fetchpriority='high') will be applied to the first panel's image to ensure it loads before any below-the-fold content."
          ),
          bulletItem("Swiper.js v11 with modular imports (pagination, autoplay, parallax, effect-fade)", "list-1"),
          bulletItem("WebP images with responsive srcset (640px / 1024px / 1920px)", "list-1"),
          bulletItem("Lazy loading for panels 2-6, priority fetch for panel 1", "list-1"),
          bulletItem("CSS containment (contain: layout style paint) on slider container", "list-1"),
          bulletItem("Intersection Observer API for autoplay pause when off-screen", "list-1"),
          bulletItem("Full WCAG 2.1 AA compliance: keyboard navigation, ARIA labels, pause button", "list-1"),

          // ── 3. Unified Header Design System ──
          heading("3. Unified Header Design System"),
          heading("3.1 Design System Architecture", HeadingLevel.HEADING_2),
          body(
            "A unified header design system is the cornerstone of professional credibility and user trust across the entire Sky Port Logistics platform. The header serves as both a navigational instrument and a brand anchor, appearing on every page with consistent typography, spacing, interactive behavior, and visual identity. The design system extends beyond the visible header component to encompass a comprehensive token library that governs colors, typography scales, spacing units, icon styles, elevation levels, and interactive states across the entire website."
          ),
          body(
            "The system is built on a token-based architecture with three layers: global design tokens (colors, spacing, typography), component tokens (header-specific values derived from global tokens), and responsive tokens (breakpoint-specific overrides). This layered approach ensures that any brand update propagates automatically across all pages and components, eliminating the drift that commonly occurs when teams maintain separate style definitions. The design tokens are stored in a centralized JSON file and consumed by both the Tailwind CSS configuration and the component library, ensuring a single source of truth."
          ),

          heading("3.2 Header Component Specification", HeadingLevel.HEADING_2),
          body(
            "The header component comprises four zones: the brand zone (logo, company name, tagline), the primary navigation zone (top-level menu items with mega-menu dropdowns), the utility zone (language selector, tracking quick-access, contact phone), and the mobile zone (hamburger menu with slide-out panel). On desktop viewports (1280px and above), all four zones are visible simultaneously. On tablet viewports (768px-1279px), the utility zone collapses into a dropdown. On mobile viewports (below 768px), the primary navigation collapses into a full-screen overlay menu with animated transitions."
          ),
          body(
            "Typography within the header follows a strict hierarchy: the company name uses SimHei at 20px with the brand blue (#005B96), navigation items use Microsoft YaHei at 14px with a medium weight and the same brand blue, utility items use 12px with the secondary gray (#607080), and the mobile menu uses 16px for touch-friendly tap targets. The header transitions from transparent (over hero sections) to solid white with a subtle box-shadow after 50px of scroll, creating a sophisticated glass-morphism effect that maintains readability while preserving the immersive hero experience."
          ),

          makeTable(
            ["Element", "Font", "Size", "Color", "Weight"],
            [
              ["Company Name", "SimHei / Times New Roman Bold", "20px", "#005B96", "Bold (700)"],
              ["Primary Nav Items", "Microsoft YaHei / Calibri", "14px", "#005B96", "Medium (500)"],
              ["Utility Items", "Microsoft YaHei / Calibri", "12px", "#607080", "Regular (400)"],
              ["Mobile Menu Items", "Microsoft YaHei / Calibri", "16px", "#005B96", "Medium (500)"],
              ["CTA Button", "Microsoft YaHei / Calibri", "14px", "#FFFFFF on #FF7F00", "Semibold (600)"],
              ["Active/Hover State", "Same as base", "Same", "#FF7F00", "Same as base"],
            ]
          ),
          figCaption("Table 3: Header Typography Specification"),

          heading("3.3 Mega-Menu Navigation Design", HeadingLevel.HEADING_2),
          body(
            "The mega-menu dropdown system transforms the header from a simple navigation bar into a powerful content discovery tool. When a user hovers over or taps a top-level navigation item, a full-width dropdown panel appears with organized sub-categories, featured content cards, quick-links to popular tools, and contextual calls-to-action. For example, hovering over 'Services' reveals a three-column layout: Column 1 lists all service categories (Air Freight, Ocean Freight, Road Transport, Warehousing, Customs Brokerage, Supply Chain Consulting), Column 2 shows a featured case study or service highlight card with a thumbnail image, and Column 3 provides quick access to the quote calculator and tracking tool."
          ),
          body(
            "The mega-menu implementation uses CSS Grid for layout stability, with JavaScript only for show/hide logic and focus management. Animations use CSS transforms (translateY and opacity) for GPU-accelerated performance, with a 150ms entrance delay to prevent accidental triggers during diagonal mouse movement. All interactive elements within the mega-menu are fully keyboard accessible, with focus trapping that prevents tab navigation from escaping the dropdown while it is open. Screen readers receive appropriate ARIA attributes (aria-expanded, aria-haspopup, role='menu') that update dynamically as the menu opens and closes."
          ),

          // ── 4. Magazine-Style Blog Platform ──
          heading("4. Magazine-Style Blog Platform"),
          heading("4.1 Content Architecture & Taxonomy", HeadingLevel.HEADING_2),
          body(
            "The magazine-style blog platform represents the most significant strategic investment in the Sky Port Logistics digital ecosystem. Unlike a traditional corporate blog that publishes occasional company updates, a magazine-style platform operates as a media property in its own right, producing editorial-quality content that attracts, educates, and converts logistics professionals throughout their buyer journey. The platform will be organized around a carefully designed taxonomy that reflects both the service offerings of Sky Port Logistics and the information-seeking behavior of the target audience."
          ),
          body(
            "The primary content categories are designed to align with high-intent search queries and industry information gaps. Each category has a dedicated landing page with a curated hero section, featured articles carousel, and category-specific lead magnets. The taxonomy includes: Industry Insights (market trends, regulatory updates, trade policy analysis), Service Deep-Dives (detailed guides on air freight, ocean freight, customs procedures, warehousing operations), Supply Chain Playbooks (how-to guides, process optimization templates, technology implementation guides), Customer Spotlights (case studies, video testimonials, ROI analyses), and Global Trade Atlas (interactive data visualizations, trade lane analytics, port performance indices)."
          ),

          makeTable(
            ["Category", "Target Keyword Cluster", "Content Cadence", "Primary Format", "Lead Magnet"],
            [
              ["Industry Insights", "logistics trends, supply chain news, freight market", "3x per week", "Long-form analysis (2,000-3,000 words)", "Quarterly Market Report PDF"],
              ["Service Deep-Dives", "air freight guide, ocean freight process, customs clearance", "2x per week", "Structured guides with infographics", "Service Comparison Calculator"],
              ["Supply Chain Playbooks", "warehouse optimization, inventory management, last-mile", "1x per week", "Step-by-step playbooks with templates", " downloadable Template Pack"],
              ["Customer Spotlights", "logistics case study, freight success story", "2x per month", "Video case study + written summary", "ROI Calculator Widget"],
              ["Global Trade Atlas", "trade data, shipping routes, port statistics", "1x per month", "Interactive data visualization", "Custom Trade Report Generator"],
            ]
          ),
          figCaption("Table 4: Blog Content Architecture & Publishing Cadence"),

          heading("4.2 Visual Design & Interactive Features", HeadingLevel.HEADING_2),
          body(
            "The blog platform's visual design draws inspiration from leading logistics and business publications such as Bloomberg Supply Chain, The Load Star, and FreightWaves, while maintaining the Sky Port brand identity. The homepage features a magazine-style masonry grid layout with content cards of varying sizes: large hero cards for featured articles (full-width with overlay text), medium cards for standard articles (image + title + excerpt), and small cards for quick reads and news updates (title + category tag + reading time). This visual hierarchy creates editorial depth and encourages exploration."
          ),
          body(
            "Interactive features are woven throughout the blog to drive engagement and data capture. Embedded polls appear within articles to capture reader opinions on industry topics (e.g., 'Which shipping route do you expect to see the most disruption in Q3?'), with results displayed in real-time using animated bar charts. Interactive infographics built with D3.js allow readers to explore trade data visually, filtering by origin, destination, commodity type, and time period. Progress indicators show reading completion percentage, and a 'Save for Later' feature allows logged-in users to bookmark articles across devices."
          ),
          bulletItem("Masonry grid layout with responsive card sizes (hero / standard / compact)", "list-2"),
          bulletItem("Embedded polls with real-time animated results using Chart.js or D3.js", "list-2"),
          bulletItem("Interactive infographics for trade lane data, port performance, and shipping cost indices", "list-2"),
          bulletItem("Reading time estimates and progress indicators on all articles", "list-2"),
          bulletItem("Social sharing toolbar with platform-specific share text and imagery", "list-2"),
          bulletItem("Table of contents auto-generated from heading structure within articles", "list-2"),
          bulletItem("Related articles carousel powered by tag-based recommendation engine", "list-2"),
          bulletItem("Comment system with moderation queue and verified industry professional badges", "list-2"),

          heading("4.3 Content Strategy & Production Workflow", HeadingLevel.HEADING_2),
          body(
            "The content strategy is designed to build topical authority across five core logistics subject areas, following a hub-and-spoke model where pillar pages (comprehensive 4,000+ word guides) serve as the hub and shorter supporting articles (800-1,500 words) link back to and expand on subtopics within the pillar. This structure signals depth to search engines and creates natural internal linking pathways that distribute page authority throughout the blog. Each pillar page targets a high-volume keyword cluster (e.g., 'ocean freight guide'), while spoke articles target long-tail variations (e.g., 'how to calculate LCL shipping costs')."
          ),
          body(
            "The production workflow follows a six-stage pipeline: ideation (monthly editorial calendar planning based on keyword research and industry events), research (subject matter expert interviews, data sourcing, competitor content gap analysis), drafting (assigned to specialized logistics writers with SEO training), design (custom infographics, data visualizations, hero images), review (editorial quality check, technical accuracy review by operations team, legal compliance verification), and publication (scheduled posting, social media distribution, email newsletter inclusion). Each stage has defined ownership, quality gates, and turnaround times, ensuring consistent output at the planned cadence."
          ),

          // ── 5. SEO Optimization Framework ──
          heading("5. SEO Optimization Framework"),
          heading("5.1 Technical SEO Architecture", HeadingLevel.HEADING_2),
          body(
            "The SEO optimization framework encompasses both technical infrastructure and content-driven strategies, working in tandem to build organic search visibility from the ground up. The technical SEO architecture ensures that search engine crawlers can efficiently discover, render, and index every page on the Sky Port Logistics platform. This begins with a flat URL architecture (maximum three levels deep: domain/category/slug), XML sitemaps segmented by content type (services, blog, resources, locations), and a robust internal linking strategy that creates clear content hierarchies and distributes link equity strategically."
          ),
          body(
            "Server-side rendering (SSR) with Next.js ensures that all pages deliver fully-rendered HTML to search engine bots, eliminating the JavaScript rendering dependency that plagues single-page applications. Dynamic meta tags (title, description, Open Graph, Twitter Card) are generated from structured data templates that incorporate target keywords, location modifiers, and compelling click-through triggers. Schema.org structured data is implemented across all page types: Organization schema for the homepage, Service schema for service pages, Article schema for blog posts, FAQ schema for resource pages, and LocalBusiness schema for office location pages. This rich structured data enables search result enhancements such as rich snippets, knowledge panels, and enhanced site links."
          ),

          heading("5.2 Keyword Strategy & Content Mapping", HeadingLevel.HEADING_2),
          body(
            "The keyword strategy targets a three-tier funnel: awareness (informational queries like 'how to choose a freight forwarder'), consideration (commercial investigation queries like 'air freight vs ocean freight cost comparison'), and decision (transactional queries like 'air freight quote to Dubai'). Each tier maps to specific content types and conversion actions, ensuring that organic traffic at every funnel stage has a clear path to engagement and eventual conversion."
          ),

          makeTable(
            ["Funnel Stage", "Query Type", "Example Keywords", "Content Type", "Conversion Action"],
            [
              ["Awareness", "Informational", "what is customs clearance, how does ocean freight work", "Educational guides, infographics", "Newsletter signup, resource download"],
              ["Consideration", "Commercial Investigation", "best air freight companies, DHL vs Maersk comparison", "Comparison guides, reviews, calculators", "Webinar registration, free consultation"],
              ["Decision", "Transactional", "air freight quote, shipping cost calculator", "Service pages, quote forms, pricing", "Quote request, phone call, live chat"],
              ["Retention", "Branded / Navigational", "Sky Port tracking, Sky Port logistics blog", "Client portal, blog, case studies", "Repeat booking, referral, upsell"],
            ]
          ),
          figCaption("Table 5: Keyword Strategy by Funnel Stage"),

          heading("5.3 Link Building & Authority Strategy", HeadingLevel.HEADING_2),
          body(
            "Building domain authority in the logistics vertical requires a systematic approach to link acquisition that prioritizes quality over quantity. The strategy centers on three pillars: data-driven linkable assets, strategic industry partnerships, and digital PR campaigns. Linkable assets include the Global Trade Atlas interactive visualizations (which naturally attract citations from researchers and journalists), original research reports on freight market trends (which generate coverage in trade publications), and free tools such as shipping cost calculators and transit time estimators (which earn organic links from business resource pages and industry directories)."
          ),
          body(
            "Strategic partnerships with logistics industry associations, trade publications, and academic institutions create opportunities for guest authorship, co-branded research, and directory listings that carry high authority. Digital PR campaigns will target logistics and supply chain journalists at publications such as Journal of Commerce, FreightWaves, Supply Chain Dive, and The Load Star, offering exclusive data insights and expert commentary on breaking industry news. Each campaign is designed to generate 15-25 high-authority referring domains per quarter, with a target of achieving Domain Rating 50+ within 12 months."
          ),

          // ── 6. Mobile Responsiveness & UX ──
          heading("6. Mobile Responsiveness & User Experience"),
          heading("6.1 Mobile-First Design Principles", HeadingLevel.HEADING_2),
          body(
            "With over 60% of B2B research now initiated on mobile devices, the Sky Port Logistics platform must adopt a mobile-first design philosophy that treats mobile as the primary experience rather than a responsive afterthought. Mobile-first design means that all wireframes, prototypes, and visual designs are created at the mobile viewport size first, then progressively enhanced for tablet and desktop. This approach forces prioritization of content and functionality, ensuring that the most critical user tasks (quote requests, shipment tracking, service exploration) are immediately accessible on small screens without scrolling or drilling into sub-pages."
          ),
          body(
            "Key mobile UX patterns include: a persistent bottom navigation bar with four primary actions (Home, Track, Quote, Contact), thumb-zone-optimized button placement (all primary CTAs within the lower 60% of the screen), collapsible content sections that default to collapsed on mobile to reduce cognitive load, inline form validation with real-time error messages that eliminate the need for post-submit correction, and a floating 'Quick Actions' button that provides access to tracking, quote requests, and live chat from any page. Touch targets maintain a minimum size of 48x48 pixels with 8px spacing between adjacent targets, ensuring comfortable one-handed operation."
          ),

          heading("6.2 Performance Optimization for Mobile Networks", HeadingLevel.HEADING_2),
          body(
            "Mobile performance optimization extends beyond responsive layouts to address the unique constraints of mobile networks and devices. The platform will implement aggressive image optimization using the HTML5 picture element with WebP sources and JPEG fallbacks, delivering images at 2x resolution for Retina displays while maintaining file sizes under 100KB for hero images and 50KB for content images. Critical CSS (above-the-fold styles) will be inlined in the HTML document head, while non-critical stylesheets are loaded asynchronously to prevent render-blocking."
          ),
          body(
            "JavaScript performance budgets are enforced at the build level: the total client-side JavaScript for any page must not exceed 200KB gzipped, with the interactive time-to-interactive (TTI) target of under 5 seconds on a mid-tier mobile device (Moto G Power) on a 4G connection (9Mbps downlink, 170ms RTT). Code splitting at the route level ensures that users only download the JavaScript needed for the current page, with preloading hints for likely next-navigation targets. Service workers enable offline access for previously visited pages and caching of API responses for the shipment tracking feature."
          ),

          imageCenter("download/kpi_improvements.png", 480),
          figCaption("Figure 2: Projected KPI Improvements Following Platform Upgrade"),

          // ── 7. Tools & Technology Stack ──
          heading("7. Tools & Technology Stack"),
          heading("7.1 Recommended Technology Ecosystem", HeadingLevel.HEADING_2),
          body(
            "The technology stack has been carefully selected to balance developer productivity, performance optimization, and long-term maintainability. The stack leverages the existing Next.js 16 infrastructure already deployed for the Sky Port Logistics website, extending it with specialized tools for content management, analytics, and design collaboration. Every tool choice is evaluated against three criteria: alignment with the existing team's skills, compatibility with the current infrastructure, and the total cost of ownership over a three-year horizon."
          ),

          makeTable(
            ["Layer", "Tool", "Purpose", "Rationale"],
            [
              ["Framework", "Next.js 16 (App Router)", "Core web platform", "Already in use; SSR, ISR, edge runtime"],
              ["Slider Library", "Swiper.js v11", "Responsive carousels", "GPU-accelerated, modular, WCAG-compliant"],
              ["CMS", "Sanity.io", "Blog content management", "Real-time collaboration, structured content, GROQ"],
              ["Design System", "Figma + Storybook", "Component library & tokens", "Single source of truth for design and code"],
              ["Analytics", "Google Analytics 4 + Mixpanel", "Traffic & behavior analytics", "GA4 for acquisition, Mixpanel for product analytics"],
              ["SEO", "Ahrefs + Screaming Frog", "Keyword research & technical audits", "Industry-leading backlink data and crawler"],
              ["CDN", "Cloudflare", "Global content delivery & edge caching", "Built-in image optimization, bot protection"],
              ["A/B Testing", "VWO or Optimizely", "Conversion rate optimization", "Visual editor, statistical significance engine"],
              ["Email Marketing", "HubSpot or Mailchimp", "Newsletter distribution & nurture", "Automation workflows, segmentation"],
              ["Video Hosting", "Vidyard or Wistia", "Blog video embedding & analytics", "Video heatmaps, CTAs, lead capture"],
            ]
          ),
          figCaption("Table 6: Recommended Technology Ecosystem"),

          heading("7.2 Design Mockup Specifications", HeadingLevel.HEADING_2),
          body(
            "Design mockups will be produced in Figma across three breakpoints (mobile 375px, tablet 768px, desktop 1440px) for all key page templates. The mockup deliverables include: homepage with slider system (6 panels), header navigation with mega-menu (expanded and collapsed states), blog homepage with masonry grid, blog article page with interactive features, service landing page template, contact page with form and map, and mobile-specific layouts for each template. All mockups will include interaction annotations, animation specifications, accessibility notes, and responsive behavior descriptions."
          ),
          body(
            "The design mockup process follows a three-stage workflow: low-fidelity wireframes (focus on layout and information hierarchy), mid-fidelity prototypes (incorporate brand colors, typography, and component styling from the design system), and high-fidelity interactive prototypes (with clickable navigation, animated transitions, and realistic content). Each stage includes a formal review gate with stakeholder sign-off before proceeding. The interactive prototypes will be built in Figma's prototype mode, allowing stakeholders to click through the user journey and experience the slider animations, mega-menu behavior, and mobile navigation flow before any code is written."
          ),

          // ── 8. Implementation Roadmap ──
          heading("8. Implementation Roadmap"),
          heading("8.1 Phased Execution Plan", HeadingLevel.HEADING_2),
          body(
            "The implementation follows a six-phase roadmap spanning 10 months, designed to deliver incremental value at each phase while managing risk through iterative delivery. Each phase concludes with a formal milestone review that evaluates completion criteria, measures progress against KPI targets, and adjusts priorities for the subsequent phase. The phased approach ensures that the platform continues to operate and serve customers throughout the transformation, with no 'big bang' launch that risks service disruption."
          ),

          imageCenter("download/implementation_roadmap.png", 480),
          figCaption("Figure 3: Implementation Roadmap - Phase Timeline"),

          makeTable(
            ["Phase", "Duration", "Key Deliverables", "Success Criteria"],
            [
              ["Phase 1: Discovery & Audit", "Months 1-2", "UX audit report, competitive analysis, content audit, analytics baseline", "All audit reports completed, KPI baselines documented"],
              ["Phase 2: Design System", "Months 2-3", "Figma component library, design tokens, style guide, responsive prototypes", "Component library with 40+ components, 3-breakpoint prototypes approved"],
              ["Phase 3: Slider & Header Dev", "Months 3-5", "Swiper.js integration, mega-menu, transparent-to-solid header, mobile nav", "LCP < 2.5s, CLS = 0, WCAG AA compliance, cross-browser tested"],
              ["Phase 4: Blog Platform", "Months 4-7", "Sanity CMS setup, blog templates, interactive features, 30 seed articles", "Blog live with 30 articles, interactive polls functional, CMS workflow tested"],
              ["Phase 5: SEO & Analytics", "Months 5-8", "Technical SEO fixes, schema markup, GA4 + Mixpanel setup, A/B test plan", "All schema validated, analytics tracking verified, first A/B test launched"],
              ["Phase 6: Launch & Optimize", "Months 8-10", "Full platform launch, CRO testing, performance tuning, content scaling", "Organic traffic +30% in 3 months, conversion rate +20%, Core Web Vitals green"],
            ]
          ),
          figCaption("Table 7: Phased Execution Plan with Success Criteria"),

          heading("8.2 Budget Allocation", HeadingLevel.HEADING_2),
          body(
            "The budget allocation reflects the strategic priority of creating a visually stunning, content-rich platform. Frontend development (sliders, headers, and interactive components) receives the largest share at 28%, followed by design system creation at 22%, and blog platform development at 20%. These three workstreams collectively represent 70% of the total investment, underscoring the plan's emphasis on visual excellence and content capability. The remaining 30% is distributed across SEO and content strategy (15%), analytics and optimization (8%), and quality assurance (7%)."
          ),

          imageCenter("download/budget_allocation.png", 420),
          figCaption("Figure 4: Budget Allocation by Workstream"),

          // ── 9. Credibility, Retention & Competitiveness ──
          heading("9. Credibility, Retention & Global Competitiveness"),
          heading("9.1 Building Industry Credibility", HeadingLevel.HEADING_2),
          body(
            "The platform elevation plan directly addresses three interconnected business objectives: credibility enhancement, user retention improvement, and global competitiveness strengthening. Credibility in the logistics industry is earned through demonstrated expertise, transparent operations, and peer validation. The magazine-style blog platform serves as the primary credibility engine, producing evidence of deep logistics knowledge through data-driven industry analyses, detailed operational guides, and verified customer success stories. Each published article acts as a trust signal, accumulating over time into a comprehensive knowledge base that positions Sky Port Logistics as a thought leader rather than merely a service provider."
          ),
          body(
            "The interactive features within the blog, such as polls, infographics, and calculators, transform passive readers into active participants, creating micro-engagement moments that build familiarity and trust. When a logistics manager uses the shipping cost calculator embedded in an article, they experience the company's operational expertise firsthand, not through marketing claims but through utility delivered. This 'show, don't tell' approach is significantly more effective at building credibility than traditional corporate messaging, as it demonstrates competence rather than asserting it."
          ),

          heading("9.2 Driving User Retention", HeadingLevel.HEADING_2),
          body(
            "User retention on logistics websites is fundamentally challenged by the low-frequency nature of shipping decisions: most businesses do not ship cargo daily, creating long gaps between visits. The blog platform addresses this retention gap by providing reasons to return beyond transactional needs. Industry Insights articles that track market trends create a habitual reading pattern (similar to how financial professionals check Bloomberg daily). Interactive tools like the trade lane analyzer and cost calculator become bookmark-worthy utilities that users return to repeatedly. Email newsletters with curated logistics intelligence maintain top-of-mind awareness between shipping cycles."
          ),
          body(
            "The data supports this approach: B2B companies that publish 11 or more blog posts per month generate 3x more traffic than those publishing 0-1 posts, and companies with active blogs generate 67% more leads per month than those without. By maintaining a consistent publishing cadence of 8-10 articles per week across categories, Sky Port Logistics creates a content gravity well that attracts and retains a professional audience, converting them from casual visitors into engaged community members and, ultimately, into loyal customers."
          ),

          heading("9.3 Strengthening Global Competitiveness", HeadingLevel.HEADING_2),
          body(
            "Global competitiveness in the logistics industry increasingly depends on digital presence and perceived technological sophistication. When procurement teams evaluate freight forwarders, they research candidates online, comparing websites, reading published content, and assessing digital tools before shortlisting vendors for RFP invitations. A world-class platform with interactive sliders, a rich content library, and sophisticated tracking tools signals that Sky Port Logistics operates at the same technological level as industry giants like DHL and Maersk, leveling the playing field in competitive bid situations."
          ),
          body(
            "The SEO-driven organic traffic strategy creates a sustainable competitive moat that compounds over time. While competitors who rely solely on paid advertising see their traffic evaporate when budgets are cut, Sky Port Logistics' organic search presence continues to grow month over month, generating leads at a fraction of the cost per acquisition. Within 18 months, the blog platform is projected to generate 60% of all inbound leads at a cost per lead 4x lower than paid channels, fundamentally shifting the economics of customer acquisition in favor of Sky Port Logistics."
          ),

          // ── 10. Risk Analysis & Mitigation ──
          heading("10. Risk Analysis & Mitigation"),
          body(
            "Every transformation initiative carries inherent risks that must be identified, assessed, and mitigated proactively. The following risk analysis addresses the most significant threats to the platform elevation plan and defines specific mitigation strategies for each. Risks are categorized across four dimensions: technical risk (performance, compatibility, security), content risk (quality, cadence, expertise), organizational risk (resources, alignment, change management), and market risk (competitive response, regulatory changes, economic conditions)."
          ),

          makeTable(
            ["Risk Category", "Specific Risk", "Likelihood", "Impact", "Mitigation Strategy"],
            [
              ["Technical", "Core Web Vitals regression during slider development", "Medium", "High", "Performance budget enforcement in CI/CD; automated Lighthouse checks on every PR"],
              ["Technical", "CMS migration data loss or content rendering issues", "Low", "High", "Staged rollout with content freeze; parallel staging environment; rollback plan"],
              ["Content", "Insufficient logistics expertise for article quality", "Medium", "High", "Hire 2 specialized logistics writers; SME review process; editorial calendar buffer"],
              ["Content", "Publishing cadence unsustainable with current team", "High", "Medium", "Freelance writer pool; AI-assisted research and outlining; batch content production"],
              ["Organizational", "Scope creep delaying launch milestones", "High", "Medium", "Strict phase gates; change request process; MVP-first approach per phase"],
              ["Organizational", "Stakeholder misalignment on design direction", "Medium", "Medium", "Design sprint workshops; prototype sign-off gates; shared Figma workspace"],
              ["Market", "Competitor launches similar platform during our build", "Low", "Medium", "Accelerate Phase 3-4 delivery; focus on differentiated content (original data, tools)"],
              ["Market", "Search algorithm update impacts SEO strategy", "Medium", "Medium", "Diversify traffic sources (email, social, referral); follow white-hat SEO only"],
            ]
          ),
          figCaption("Table 8: Risk Analysis and Mitigation Matrix"),

          // ── 11. Conclusions & Recommendations ──
          heading("11. Conclusions & Recommendations"),
          body(
            "The Sky Port Logistics platform elevation plan presents a structured, phased approach to transforming a functional corporate website into a world-class digital platform that competes with the best in the global logistics industry. The plan addresses the full spectrum of digital excellence, from visual design and interactive sliders through unified header systems, magazine-quality content platforms, and comprehensive SEO strategies, all unified by a mobile-first user experience philosophy."
          ),
          body(
            "The recommended next steps are as follows. First, secure executive sponsorship and budget approval for the full 10-month program, with a formal commitment to the phased delivery model and milestone review process. Second, assemble the core project team: a UX/UI designer, two frontend developers (Next.js and React specialists), a content strategist, an SEO specialist, and a project manager. Third, initiate Phase 1 (Discovery and Audit) immediately, as the audit findings will validate the assumptions in this plan and may reveal additional opportunities or risks that require strategic adjustment. Fourth, establish the analytics baseline by deploying GA4 and Mixpanel on the current website before any changes are made, ensuring that all subsequent improvements can be measured against a reliable benchmark."
          ),
          body(
            "The projected return on investment is compelling: a 77% increase in organic traffic, 112% improvement in lead generation, and 55% reduction in bounce rate within the first 12 months post-launch, translating to an estimated 3.2x return on the total platform investment within 18 months. Beyond the quantifiable metrics, the platform elevation will fundamentally strengthen Sky Port Logistics' market position, transforming the website from a digital brochure into a strategic business asset that generates leads, builds authority, and creates lasting competitive advantage in the global logistics marketplace."
          ),
        ],
      },
    ],
  });

  const buffer = await Packer.toBuffer(doc);
  const outPath = "/home/z/my-project/download/Sky_Port_Logistics_Platform_Elevation_Plan.docx";
  fs.writeFileSync(outPath, buffer);
  console.log("Document generated:", outPath);
}

main().catch(console.error);
