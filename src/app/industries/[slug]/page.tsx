"use client";

import { useParams } from "next/navigation";
import Link from "next/link";
import {
  Factory,
  Pill,
  Cpu,
  ShoppingBag,
  Zap,
  UtensilsCrossed,
  ArrowRight,
  CheckCircle,
  Shield,
  Clock,
  Globe,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import AnimatedSection from "@/components/AnimatedSection";

const industryData: Record<string, {
  icon: React.ComponentType<{ className?: string; size?: number }>;
  title: string;
  subtitle: string;
  color: string;
  heroGradient: string;
  description: string;
  longDescription: string;
  challenges: { title: string; description: string }[];
  solutions: { icon: React.ComponentType<{ className?: string; size?: number }>; title: string; description: string }[];
  caseStudy: { title: string; client: string; result: string; description: string };
}> = {
  automotive: {
    icon: Factory,
    title: "Automotive",
    subtitle: "Vehicle & Parts Logistics",
    color: "from-slate-600 to-slate-800",
    heroGradient: "from-slate-800/90 via-slate-700/80 to-slate-900/70",
    description: "Just-in-time delivery and specialized handling for the automotive industry.",
    longDescription: "Sky Port Logistics provides comprehensive logistics solutions for the automotive industry, including just-in-time (JIT) delivery, vendor-managed inventory (VMI), and specialized handling for oversized components. Our global network connects manufacturing hubs across Asia, Europe, and the Americas with seamless multimodal transport solutions designed to keep your production lines running.",
    challenges: [
      { title: "Just-in-Time Pressure", description: "Automotive production lines require precise timing — delays cost thousands per minute. Our JIT delivery systems ensure parts arrive exactly when needed." },
      { title: "Oversized Components", description: "Engines, transmissions, and body panels require specialized handling and equipment. Our project cargo team manages every detail." },
      { title: "Global Supply Chains", description: "Parts sourced from dozens of countries create complex logistics networks. Our integrated solutions simplify the complexity." },
    ],
    solutions: [
      { icon: Clock, title: "JIT Delivery", description: "Time-definite delivery windows with real-time tracking and proactive exception management." },
      { icon: Shield, title: "VMI Programs", description: "Vendor-managed inventory at strategic locations near your production facilities." },
      { icon: Globe, title: "Multimodal Transport", description: "Optimized air, ocean, and road combinations for cost and speed efficiency." },
    ],
    caseStudy: {
      title: "Global Auto Parts Distribution",
      client: "Major European Automaker",
      result: "35% reduction in logistics costs",
      description: "Redesigned the entire inbound logistics network for a leading European automaker, consolidating shipments from 200+ Asian suppliers through our regional hub, reducing lead times by 40% and logistics costs by 35%.",
    },
  },
  pharmaceutical: {
    icon: Pill,
    title: "Pharmaceutical",
    subtitle: "GDP-Compliant Cold Chain Logistics",
    color: "from-emerald-500 to-emerald-700",
    heroGradient: "from-emerald-800/90 via-emerald-700/80 to-emerald-900/70",
    description: "Temperature-controlled transport with unbroken cold chain integrity for pharmaceuticals.",
    longDescription: "Sky Port Logistics delivers GDP-compliant temperature-controlled logistics solutions for the pharmaceutical and life sciences industry. From clinical trial materials to commercial product distribution, our cold chain experts ensure unbroken temperature integrity from origin to destination, supported by 24/7 monitoring and comprehensive documentation.",
    challenges: [
      { title: "Temperature Integrity", description: "Pharmaceutical products require strict temperature control (2-8°C, 15-25°C) throughout transit. Any deviation can compromise efficacy and safety." },
      { title: "Regulatory Compliance", description: "GDP, GMP, and local regulatory requirements vary by country. Our compliance team navigates these complexities on your behalf." },
      { title: "Time-Sensitive Shipments", description: "Clinical trials and emergency medical supplies demand rapid, reliable delivery with zero tolerance for delays." },
    ],
    solutions: [
      { icon: Shield, title: "GDP Compliance", description: "Full GDP compliance with validated cold chain processes, calibrated equipment, and comprehensive documentation." },
      { icon: Clock, title: "24/7 Monitoring", description: "Real-time temperature monitoring with automated alerts and intervention protocols for every shipment." },
      { icon: Globe, title: "Global Reach", description: "Temperature-controlled transport to 150+ countries with local regulatory expertise at every destination." },
    ],
    caseStudy: {
      title: "Global Vaccine Distribution",
      client: "Top 10 Pharma Company",
      result: "Zero temperature excursions in 10,000+ shipments",
      description: "Managed the global distribution of temperature-sensitive vaccines across 80 countries, maintaining 2-8°C integrity with zero excursions across more than 10,000 shipments over 18 months.",
    },
  },
  technology: {
    icon: Cpu,
    title: "Technology",
    subtitle: "Secure High-Value Tech Logistics",
    color: "from-violet-500 to-violet-700",
    heroGradient: "from-violet-800/90 via-violet-700/80 to-violet-900/70",
    description: "Secure, high-value technology shipments with anti-static handling and chain-of-custody tracking.",
    longDescription: "Sky Port Logistics specializes in the secure transport of high-value technology products, from semiconductors and servers to consumer electronics and data center equipment. Our technology logistics solutions include anti-static handling, chain-of-custody tracking, white-glove delivery, and comprehensive cargo insurance to protect your most valuable assets.",
    challenges: [
      { title: "High Value, High Risk", description: "Tech products are high-value targets for theft and require enhanced security measures throughout the supply chain." },
      { title: "ESD Sensitivity", description: "Electronic components are vulnerable to electrostatic discharge. Our anti-static handling protocols protect your products." },
      { title: "Rapid Product Cycles", description: "Short product lifecycles demand agile logistics with fast time-to-market delivery for launches and updates." },
    ],
    solutions: [
      { icon: Shield, title: "Secure Transport", description: "GPS-tracked vehicles, tamper-evident seals, and chain-of-custody documentation for complete security." },
      { icon: Clock, title: "White-Glove Service", description: "Specialized handling with anti-static packaging and trained personnel for sensitive electronics." },
      { icon: Globe, title: "Launch Logistics", description: "Coordinated global product launch logistics with simultaneous delivery to multiple destinations." },
    ],
    caseStudy: {
      title: "Global Product Launch",
      client: "Leading Tech Manufacturer",
      result: "Simultaneous delivery to 45 countries",
      description: "Coordinated the simultaneous global launch of a flagship product, delivering 500,000 units to 45 countries on the same day with zero security incidents and 100% on-time delivery.",
    },
  },
  retail: {
    icon: ShoppingBag,
    title: "Retail & E-Commerce",
    subtitle: "Fast Fulfillment & Distribution",
    color: "from-rose-500 to-rose-700",
    heroGradient: "from-rose-800/90 via-rose-700/80 to-rose-900/70",
    description: "Multi-channel fulfillment and last-mile delivery for retail and e-commerce businesses.",
    longDescription: "Sky Port Logistics powers the retail and e-commerce supply chain with fast fulfillment, multi-channel distribution, and efficient last-mile delivery. From B2B store replenishment to B2C direct-to-consumer shipping, our integrated solutions help retailers meet ever-increasing customer expectations for speed and convenience.",
    challenges: [
      { title: "Seasonal Peaks", description: "Black Friday, holiday seasons, and flash sales create massive demand spikes. Our scalable infrastructure handles peak volumes with ease." },
      { title: "Omnichannel Complexity", description: "Customers expect seamless shopping across online, mobile, and in-store channels. Our integrated fulfillment supports every channel." },
      { title: "Returns Management", description: "Efficient returns processing is critical for customer satisfaction and cost control. Our reverse logistics solutions streamline the process." },
    ],
    solutions: [
      { icon: Clock, title: "Same-Day Fulfillment", description: "Order processing and dispatch within hours, with same-day and next-day delivery options for major markets." },
      { icon: Globe, title: "Multi-Channel Distribution", description: "Integrated fulfillment for online, wholesale, and retail channels from strategically located distribution centers." },
      { icon: Shield, title: "Returns Management", description: "Efficient reverse logistics with automated returns processing, quality inspection, and restocking." },
    ],
    caseStudy: {
      title: "E-Commerce Fulfillment Transformation",
      client: "Fast-Growing D2C Brand",
      result: "60% faster order processing",
      description: "Transformed fulfillment operations for a direct-to-consumer brand, reducing order processing time by 60% and enabling same-day dispatch for 95% of orders from our strategically located fulfillment centers.",
    },
  },
  energy: {
    icon: Zap,
    title: "Energy & Mining",
    subtitle: "Heavy Equipment & HAZMAT Logistics",
    color: "from-amber-500 to-amber-700",
    heroGradient: "from-amber-800/90 via-amber-700/80 to-amber-900/70",
    description: "Specialized logistics for heavy equipment, hazardous materials, and energy sector operations.",
    longDescription: "Sky Port Logistics provides specialized logistics solutions for the energy and mining sectors, including heavy equipment transport, hazardous materials (HAZMAT) handling, and supply chain management for remote mining and energy operations. Our experienced team manages the unique challenges of moving oversized, overweight, and hazardous cargo to some of the world's most remote locations.",
    challenges: [
      { title: "Oversized Equipment", description: "Drilling rigs, turbines, and mining equipment exceed standard transport dimensions. Our project cargo experts handle every detail." },
      { title: "Remote Locations", description: "Mining and energy sites are often in remote areas with limited infrastructure. Our network reaches where others can't." },
      { title: "HAZMAT Compliance", description: "Strict regulations govern the transport of hazardous materials. Our HAZMAT-certified team ensures full compliance." },
    ],
    solutions: [
      { icon: Shield, title: "HAZMAT Certified", description: "IATA, IMDG, and ADR-certified handling of dangerous goods with proper documentation and packaging." },
      { icon: Globe, title: "Project Cargo", description: "End-to-end project cargo management including route surveys, permits, and specialized equipment." },
      { icon: Clock, title: "Remote Delivery", description: "Last-mile delivery to remote mining and energy sites with charter flights and off-road transport." },
    ],
    caseStudy: {
      title: "Mining Equipment Delivery",
      client: "International Mining Corporation",
      result: "Successful delivery of 200-ton equipment to remote site",
      description: "Managed the transport of a 200-ton mining excavator from Germany to a remote site in Western Australia, including ocean freight, road transport with special permits, and final delivery via custom-built trailers.",
    },
  },
  "food-beverage": {
    icon: UtensilsCrossed,
    title: "Food & Beverage",
    subtitle: "Cold Chain & Perishable Logistics",
    color: "from-[#005B96] to-[#003D66]",
    heroGradient: "from-[#003D66]/90 via-[#005B96]/80 to-[#003D66]/70",
    description: "Temperature-monitored cold chain logistics for perishable goods with HACCP compliance.",
    longDescription: "Sky Port Logistics delivers comprehensive cold chain and perishable goods logistics for the food and beverage industry. From farm to fork, our temperature-monitored transport, HACCP-compliant handling, and time-definite delivery ensure your products arrive fresh, safe, and on time. We specialize in fresh produce, frozen foods, beverages, and temperature-sensitive ingredients.",
    challenges: [
      { title: "Shelf Life Management", description: "Perishable goods have limited shelf life. Every hour in transit matters. Our time-definite solutions minimize transit time." },
      { title: "Temperature Control", description: "Different products require different temperatures. Our multi-temperature fleet handles fresh, chilled, and frozen cargo simultaneously." },
      { title: "Food Safety", description: "HACCP compliance and food safety certifications are non-negotiable. Our processes meet the highest standards." },
    ],
    solutions: [
      { icon: Shield, title: "HACCP Compliance", description: "Full HACCP-compliant handling with food-grade vehicles, temperature monitoring, and hygiene protocols." },
      { icon: Clock, title: "Time-Definite Delivery", description: "Guaranteed delivery windows with priority handling for time-sensitive perishable shipments." },
      { icon: Globe, title: "Multi-Temperature Fleet", description: "Refrigerated vehicles with multi-compartment temperature control for mixed fresh, chilled, and frozen loads." },
    ],
    caseStudy: {
      title: "Fresh Produce Export Program",
      client: "Major Fruit Exporter",
      result: "Zero spoilage across 5,000+ shipments",
      description: "Established a dedicated cold chain program for fresh fruit exports from South America to Europe and Asia, achieving zero spoilage across more than 5,000 shipments over two years with consistent 2-5°C temperature control.",
    },
  },
};

const allIndustries = [
  { slug: "automotive", icon: Factory, title: "Automotive" },
  { slug: "pharmaceutical", icon: Pill, title: "Pharmaceutical" },
  { slug: "technology", icon: Cpu, title: "Technology" },
  { slug: "retail", icon: ShoppingBag, title: "Retail & E-Commerce" },
  { slug: "energy", icon: Zap, title: "Energy & Mining" },
  { slug: "food-beverage", icon: UtensilsCrossed, title: "Food & Beverage" },
];

export default function IndustryPage() {
  const params = useParams();
  const slug = params.slug as string;
  const industry = industryData[slug];

  if (!industry) {
    return (
      <div className="pt-20 min-h-screen flex items-center justify-center">
        <div className="text-center">
          <h1 className="text-4xl font-bold text-[#005B96] mb-4">Industry Not Found</h1>
          <p className="text-gray-600 mb-6">The industry you are looking for does not exist.</p>
          <Link href="/industries">
            <Button className="bg-[#FF7F00] hover:bg-[#CC6600] text-white rounded-full px-6">
              Back to Industries
            </Button>
          </Link>
        </div>
      </div>
    );
  }

  const Icon = industry.icon;
  const relatedIndustries = allIndustries.filter((i) => i.slug !== slug).slice(0, 3);

  return (
    <div className="pt-20">
      {/* Hero */}
      <section className="relative py-24 overflow-hidden">
        <div className={`absolute inset-0 bg-gradient-to-r ${industry.heroGradient}`} />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection>
            <div className="max-w-3xl">
              <div className="flex items-center gap-3 mb-6">
                <div className="bg-white/20 backdrop-blur-sm rounded-full p-3">
                  <Icon className="text-white" size={32} />
                </div>
                <span className="text-white/80 text-lg">{industry.subtitle}</span>
              </div>
              <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
                {industry.title} Logistics
              </h1>
              <p className="text-white/80 text-lg mb-8 max-w-2xl">
                {industry.description}
              </p>
              <Link href="/contact">
                <Button
                  size="lg"
                  className="bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold px-8 py-6 text-lg rounded-full shadow-xl group"
                >
                  Get a Quote
                  <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
                </Button>
              </Link>
            </div>
          </AnimatedSection>
        </div>
      </section>

      {/* Overview */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <AnimatedSection>
              <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
                Industry Overview
              </span>
              <h2 className="text-3xl sm:text-4xl font-bold text-[#005B96] mt-3 mb-6">
                Specialized Solutions for {industry.title}
              </h2>
              <p className="text-gray-600 leading-relaxed">
                {industry.longDescription}
              </p>
            </AnimatedSection>
            <AnimatedSection delay={0.2}>
              <div className={`relative rounded-2xl overflow-hidden h-80 bg-gradient-to-br ${industry.color} flex items-center justify-center`}>
                <Icon className="text-white/20" size={200} />
                <div className="absolute inset-0 flex items-center justify-center">
                  <div className="bg-white/20 backdrop-blur-sm rounded-full p-8">
                    <Icon className="text-white" size={64} />
                  </div>
                </div>
              </div>
            </AnimatedSection>
          </div>
        </div>
      </section>

      {/* Challenges */}
      <section className="py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-16">
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              Challenges
            </span>
            <h2 className="text-3xl sm:text-4xl font-bold text-[#005B96] mt-3">
              Industry Challenges We Solve
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {industry.challenges.map((challenge, idx) => (
              <AnimatedSection key={challenge.title} delay={idx * 0.1}>
                <Card className="border-0 shadow-lg h-full">
                  <CardContent className="p-6">
                    <div className="bg-[#FF7F00]/10 rounded-xl w-12 h-12 flex items-center justify-center mb-4">
                      <span className="text-[#FF7F00] font-bold text-xl">{idx + 1}</span>
                    </div>
                    <h3 className="text-lg font-bold text-[#005B96] mb-3">{challenge.title}</h3>
                    <p className="text-gray-600 leading-relaxed text-sm">{challenge.description}</p>
                  </CardContent>
                </Card>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* Solutions */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-16">
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              Our Solutions
            </span>
            <h2 className="text-3xl sm:text-4xl font-bold text-[#005B96] mt-3">
              How We Help
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {industry.solutions.map((solution, idx) => {
              const SolIcon = solution.icon;
              return (
                <AnimatedSection key={solution.title} delay={idx * 0.1}>
                  <Card className="border-0 shadow-md hover:shadow-xl transition-all duration-300 h-full">
                    <CardContent className="p-6">
                      <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-14 h-14 flex items-center justify-center mb-5">
                        <SolIcon className="text-white" size={24} />
                      </div>
                      <h3 className="text-lg font-bold text-[#005B96] mb-3">{solution.title}</h3>
                      <p className="text-gray-600 leading-relaxed text-sm">{solution.description}</p>
                    </CardContent>
                  </Card>
                </AnimatedSection>
              );
            })}
          </div>
        </div>
      </section>

      {/* Case Study */}
      <section className="py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection>
            <Card className="border-0 shadow-xl overflow-hidden">
              <CardContent className="p-0">
                <div className="grid grid-cols-1 lg:grid-cols-2">
                  <div className={`bg-gradient-to-br ${industry.color} p-8 sm:p-12 flex items-center`}>
                    <div className="text-white">
                      <span className="bg-white/20 text-white text-sm font-medium px-3 py-1 rounded-full">Case Study</span>
                      <h3 className="text-2xl sm:text-3xl font-bold mt-4 mb-3">{industry.caseStudy.title}</h3>
                      <p className="text-white/80 mb-2">Client: {industry.caseStudy.client}</p>
                      <div className="text-[#FFA040] font-bold text-xl">Result: {industry.caseStudy.result}</div>
                    </div>
                  </div>
                  <div className="p-8 sm:p-12 flex items-center">
                    <p className="text-gray-600 leading-relaxed">{industry.caseStudy.description}</p>
                  </div>
                </div>
              </CardContent>
            </Card>
          </AnimatedSection>
        </div>
      </section>

      {/* CTA */}
      <section className="py-20 bg-gradient-to-r from-[#005B96] to-[#003D66] relative overflow-hidden">
        <div
          className="absolute inset-0 opacity-10"
          style={{
            backgroundImage: `url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='1' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E")`,
          }}
        />
        <div className="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <AnimatedSection>
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
              Need {industry.title} Logistics?
            </h2>
            <p className="text-white/80 text-lg mb-8 max-w-2xl mx-auto">
              Our industry specialists will design a tailored solution for your
              specific challenges. Get a free consultation today.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link href="/contact">
                <Button
                  size="lg"
                  className="bg-[#FF7F00] hover:bg-[#CC6600] text-white font-bold px-8 py-6 text-lg rounded-full shadow-xl group"
                >
                  Get Free Quote
                  <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
                </Button>
              </Link>
              <Link href="/industries">
                <Button
                  size="lg"
                  variant="outline"
                  className="border-white/30 text-white hover:bg-white/10 font-bold px-8 py-6 text-lg rounded-full"
                >
                  View All Industries
                </Button>
              </Link>
            </div>
          </AnimatedSection>
        </div>
      </section>

      {/* Related Industries */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-12">
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              Explore More
            </span>
            <h2 className="text-3xl sm:text-4xl font-bold text-[#005B96] mt-3">
              Related Industries
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-8">
            {relatedIndustries.map((related, idx) => (
              <AnimatedSection key={related.slug} delay={idx * 0.1}>
                <Link href={`/industries/${related.slug}`}>
                  <Card className="group border-0 shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer">
                    <CardContent className="p-6 text-center">
                      <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-14 h-14 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <related.icon className="text-white" size={24} />
                      </div>
                      <h3 className="text-lg font-bold text-[#005B96] mb-2">
                        {related.title}
                      </h3>
                      <span className="inline-flex items-center gap-1 text-[#FF7F00] text-sm font-semibold group-hover:gap-2 transition-all">
                        Learn More <ArrowRight size={14} />
                      </span>
                    </CardContent>
                  </Card>
                </Link>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
