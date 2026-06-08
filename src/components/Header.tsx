"use client";

import { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import Link from "next/link";
import { usePathname } from "next/navigation";
import {
  Plane,
  Ship,
  Truck,
  Warehouse,
  ChevronDown,
  Menu,
  X,
  ArrowRight,
  Globe,
  Factory,
  Pill,
  Cpu,
  ShoppingBag,
  Zap,
  UtensilsCrossed,
  BookOpen,
  FileText,
  Newspaper,
  ChevronRight,
} from "lucide-react";
import { Button } from "@/components/ui/button";

const navItems = [
  {
    label: "Home",
    href: "/",
    type: "link" as const,
  },
  {
    label: "Services",
    href: "/services",
    type: "mega" as const,
    columns: [
      {
        title: "Our Services",
        links: [
          {
            icon: Plane,
            title: "Air Freight",
            description: "Express air cargo with guaranteed delivery",
            href: "/services/air-freight",
          },
          {
            icon: Ship,
            title: "Ocean Freight",
            description: "FCL & LCL ocean shipping solutions",
            href: "/services/ocean-freight",
          },
          {
            icon: Truck,
            title: "Road Transport",
            description: "GPS-tracked road freight & last-mile",
            href: "/services/road-transport",
          },
          {
            icon: Warehouse,
            title: "Warehousing",
            description: "State-of-the-art storage & distribution",
            href: "/services/warehousing",
          },
        ],
      },
      {
        title: "Featured",
        featured: {
          image: "/cargo-plane.png",
          title: "End-to-End Logistics",
          description:
            "From pickup to delivery, customs to warehousing — we handle it all with precision and care.",
          cta: { text: "Explore All Services", href: "/services" },
        },
      },
    ],
  },
  {
    label: "About",
    href: "/about",
    type: "dropdown" as const,
    links: [
      { icon: Globe, title: "Company Overview", href: "/about" },
      { icon: Zap, title: "Our Network", href: "/about#network" },
      { icon: Factory, title: "Careers", href: "/about#careers" },
    ],
  },
  {
    label: "Industries",
    href: "/industries",
    type: "mega" as const,
    columns: [
      {
        title: "Industries",
        links: [
          {
            icon: Factory,
            title: "Automotive",
            description: "Vehicle & parts logistics worldwide",
            href: "/industries/automotive",
          },
          {
            icon: Pill,
            title: "Pharmaceutical",
            description: "Temperature-controlled pharma transport",
            href: "/industries/pharmaceutical",
          },
          {
            icon: Cpu,
            title: "Technology",
            description: "Secure high-value tech shipments",
            href: "/industries/technology",
          },
        ],
      },
      {
        title: "More Industries",
        links: [
          {
            icon: ShoppingBag,
            title: "Retail & E-Commerce",
            description: "Fast fulfillment & last-mile delivery",
            href: "/industries/retail",
          },
          {
            icon: Zap,
            title: "Energy & Mining",
            description: "Heavy equipment & hazardous materials",
            href: "/industries/energy",
          },
          {
            icon: UtensilsCrossed,
            title: "Food & Beverage",
            description: "Cold chain & perishable goods logistics",
            href: "/industries/food-beverage",
          },
        ],
      },
      {
        title: "Featured",
        featured: {
          image: "/global-network.png",
          title: "Industry Solutions",
          description:
            "Tailored logistics solutions designed for the unique challenges of your industry.",
          cta: { text: "View All Industries", href: "/industries" },
        },
      },
    ],
  },
  {
    label: "Insights",
    href: "/blog",
    type: "dropdown" as const,
    links: [
      { icon: BookOpen, title: "Blog", href: "/blog" },
      { icon: FileText, title: "Case Studies", href: "/blog?category=case-studies" },
      { icon: Newspaper, title: "News & Updates", href: "/blog?category=news" },
    ],
  },
  {
    label: "Track & Trace",
    href: "/tracking",
    type: "link" as const,
  },
];

export default function Header() {
  const pathname = usePathname();
  const isHome = pathname === "/";
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState<string | null>(null);
  const [mobileAccordion, setMobileAccordion] = useState<string | null>(null);

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 50);
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const closeMobileMenu = () => {
    setIsMobileMenuOpen(false);
    setMobileAccordion(null);
  };

  const isSolid = !isHome || isScrolled;
  const textColor = isSolid ? "text-[#005B96]" : "text-white";
  const accentColor = isSolid ? "text-[#FF7F00]" : "text-[#FFA040]";

  return (
    <header
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isSolid
          ? "bg-white/95 backdrop-blur-md shadow-lg"
          : "bg-transparent"
      }`}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <Link href="/" className="flex items-center gap-3">
            <img
              src="/logo.jpeg"
              alt="Sky Port Logistics"
              className="h-12 w-12 rounded-full object-cover ring-2 ring-[#FF7F00]"
            />
            <div>
              <span
                className={`text-xl font-bold tracking-tight ${textColor}`}
              >
                SKY PORT
              </span>
              <span
                className={`block text-[10px] tracking-[0.3em] uppercase ${accentColor}`}
              >
                Logistics
              </span>
            </div>
          </Link>

          {/* Desktop Nav */}
          <nav className="hidden lg:flex items-center gap-1">
            {navItems.map((item) => {
              if (item.type === "link") {
                return (
                  <Link
                    key={item.label}
                    href={item.href}
                    className={`px-4 py-2 text-sm font-medium transition-colors hover:text-[#FF7F00] rounded-lg ${
                      pathname === item.href
                        ? "text-[#FF7F00]"
                        : textColor
                    }`}
                  >
                    {item.label}
                  </Link>
                );
              }

              return (
                <div
                  key={item.label}
                  className="relative"
                  onMouseEnter={() => setActiveDropdown(item.label)}
                  onMouseLeave={() => setActiveDropdown(null)}
                >
                  <Link
                    href={item.href}
                    className={`px-4 py-2 text-sm font-medium transition-colors hover:text-[#FF7F00] rounded-lg flex items-center gap-1 ${
                      pathname.startsWith(item.href)
                        ? "text-[#FF7F00]"
                        : textColor
                    }`}
                  >
                    {item.label}
                    <ChevronDown
                      size={14}
                      className={`transition-transform duration-200 ${
                        activeDropdown === item.label ? "rotate-180" : ""
                      }`}
                    />
                  </Link>

                  {/* Dropdown / Mega Menu */}
                  <AnimatePresence>
                    {activeDropdown === item.label && (
                      <motion.div
                        initial={{ opacity: 0, y: 10 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: 10 }}
                        transition={{ duration: 0.2 }}
                        className={
                          item.type === "mega"
                            ? "absolute left-1/2 -translate-x-1/2 top-full pt-2"
                            : "absolute left-0 top-full pt-2"
                        }
                      >
                        {item.type === "mega" && item.columns ? (
                          <div className="bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 w-[720px]">
                            <div className="grid grid-cols-3 gap-6">
                              {item.columns.map((col, colIdx) => {
                                if ("featured" in col && col.featured) {
                                  return (
                                    <div
                                      key={colIdx}
                                      className="relative rounded-xl overflow-hidden group"
                                    >
                                      <img
                                        src={col.featured.image}
                                        alt={col.featured.title}
                                        className="w-full h-full object-cover min-h-[240px] group-hover:scale-105 transition-transform duration-500"
                                      />
                                      <div className="absolute inset-0 bg-gradient-to-t from-[#003D66]/90 via-[#005B96]/60 to-transparent" />
                                      <div className="absolute inset-0 p-5 flex flex-col justify-end">
                                        <h4 className="text-white font-bold text-lg mb-2">
                                          {col.featured.title}
                                        </h4>
                                        <p className="text-white/80 text-sm mb-4">
                                          {col.featured.description}
                                        </p>
                                        <Link
                                          href={col.featured.cta.href}
                                          className="inline-flex items-center gap-1 text-[#FFA040] text-sm font-semibold hover:text-[#FF7F00] transition-colors"
                                        >
                                          {col.featured.cta.text}
                                          <ArrowRight size={14} />
                                        </Link>
                                      </div>
                                    </div>
                                  );
                                }

                                return (
                                  <div key={colIdx}>
                                    {col.title && (
                                      <h4 className="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                                        {col.title}
                                      </h4>
                                    )}
                                    <div className="space-y-1">
                                      {col.links?.map((link) => {
                                        const Icon = link.icon;
                                        return (
                                          <Link
                                            key={link.href}
                                            href={link.href}
                                            className="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group"
                                          >
                                            <div className="bg-[#005B96]/10 rounded-lg p-2 mt-0.5 group-hover:bg-[#005B96]/20 transition-colors">
                                              <Icon
                                                className="text-[#005B96]"
                                                size={18}
                                              />
                                            </div>
                                            <div>
                                              <div className="font-semibold text-[#005B96] text-sm group-hover:text-[#0078C8] transition-colors">
                                                {link.title}
                                              </div>
                                              <div className="text-gray-500 text-xs mt-0.5">
                                                {link.description}
                                              </div>
                                            </div>
                                          </Link>
                                        );
                                      })}
                                    </div>
                                  </div>
                                );
                              })}
                            </div>
                          </div>
                        ) : (
                          <div className="bg-white rounded-xl shadow-2xl border border-gray-100 p-2 min-w-[220px]">
                            {item.links?.map((link) => {
                              const Icon = link.icon;
                              return (
                                <Link
                                  key={link.href}
                                  href={link.href}
                                  className="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors group"
                                >
                                  <Icon
                                    className="text-[#005B96] group-hover:text-[#FF7F00] transition-colors"
                                    size={18}
                                  />
                                  <span className="font-medium text-[#005B96] text-sm group-hover:text-[#FF7F00] transition-colors">
                                    {link.title}
                                  </span>
                                </Link>
                              );
                            })}
                          </div>
                        )}
                      </motion.div>
                    )}
                  </AnimatePresence>
                </div>
              );
            })}

            <Link href="/contact">
              <Button className="bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold px-6 rounded-full shadow-lg hover:shadow-xl transition-all ml-4">
                Contact Us
              </Button>
            </Link>
          </nav>

          {/* Mobile Menu Toggle */}
          <button
            className="lg:hidden"
            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
            aria-label="Toggle menu"
          >
            {isMobileMenuOpen ? (
              <X className={isSolid ? "text-[#005B96]" : "text-white"} size={28} />
            ) : (
              <Menu className={isSolid ? "text-[#005B96]" : "text-white"} size={28} />
            )}
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      <AnimatePresence>
        {isMobileMenuOpen && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: "auto" }}
            exit={{ opacity: 0, height: 0 }}
            className="lg:hidden bg-white shadow-xl border-t"
          >
            <div className="max-h-[80vh] overflow-y-auto px-4 py-6 space-y-2">
              {navItems.map((item) => {
                if (item.type === "link") {
                  return (
                    <Link
                      key={item.label}
                      href={item.href}
                      onClick={closeMobileMenu}
                      className="block text-[#005B96] font-medium py-3 px-3 hover:text-[#FF7F00] hover:bg-gray-50 rounded-lg transition-colors"
                    >
                      {item.label}
                    </Link>
                  );
                }

                return (
                  <div key={item.label}>
                    <button
                      onClick={() =>
                        setMobileAccordion(
                          mobileAccordion === item.label ? null : item.label
                        )
                      }
                      className="w-full flex items-center justify-between text-[#005B96] font-medium py-3 px-3 hover:text-[#FF7F00] hover:bg-gray-50 rounded-lg transition-colors"
                    >
                      {item.label}
                      <ChevronDown
                        size={16}
                        className={`transition-transform ${
                          mobileAccordion === item.label ? "rotate-180" : ""
                        }`}
                      />
                    </button>
                    <AnimatePresence>
                      {mobileAccordion === item.label && (
                        <motion.div
                          initial={{ height: 0, opacity: 0 }}
                          animate={{ height: "auto", opacity: 1 }}
                          exit={{ height: 0, opacity: 0 }}
                          transition={{ duration: 0.2 }}
                          className="overflow-hidden"
                        >
                          <div className="pl-4 pb-2 space-y-1">
                            {(() => {
                              const links = item.type === "mega" && item.columns
                                ? item.columns.flatMap((col) => col.links ?? [])
                                : item.links ?? [];
                              return links.map((link) => {
                                const Icon = link.icon;
                                return (
                                  <Link
                                    key={link.href}
                                    href={link.href}
                                    onClick={closeMobileMenu}
                                    className="flex items-center gap-3 py-2 px-3 text-sm text-gray-600 hover:text-[#FF7F00] hover:bg-gray-50 rounded-lg transition-colors"
                                  >
                                    <Icon size={16} className="text-[#005B96]" />
                                    {link.title}
                                  </Link>
                                );
                              });
                            })()}
                            <Link
                              href={item.href}
                              onClick={closeMobileMenu}
                              className="flex items-center gap-2 py-2 px-3 text-sm font-semibold text-[#FF7F00] hover:bg-orange-50 rounded-lg transition-colors"
                            >
                              View All
                              <ChevronRight size={14} />
                            </Link>
                          </div>
                        </motion.div>
                      )}
                    </AnimatePresence>
                  </div>
                );
              })}
              <div className="pt-4">
                <Link href="/contact" onClick={closeMobileMenu}>
                  <Button className="w-full bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold rounded-full h-12">
                    Contact Us
                  </Button>
                </Link>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  );
}
