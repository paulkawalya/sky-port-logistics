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
  Phone,
  Navigation,
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
        title: "Transport Solutions",
        links: [
          { icon: Plane, title: "Air Freight", description: "Express air cargo worldwide", href: "/services/air-freight" },
          { icon: Ship, title: "Ocean Freight", description: "FCL & LCL ocean shipping", href: "/services/ocean-freight" },
          { icon: Truck, title: "Road Transport", description: "GPS-tracked road freight", href: "/services/road-transport" },
        ],
      },
      {
        title: "Supply Chain",
        links: [
          { icon: Warehouse, title: "Warehousing", description: "Storage & distribution", href: "/services/warehousing" },
          { icon: Zap, title: "Express Delivery", description: "Time-critical shipments", href: "/services" },
          { icon: Globe, title: "Customs Brokerage", description: "Import & export clearance", href: "/services" },
        ],
      },
      {
        title: "Featured",
        featured: {
          image: "/cargo-plane.png",
          title: "End-to-End Logistics",
          description: "From pickup to delivery, customs to warehousing — we handle it all with precision and care.",
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
          { icon: Factory, title: "Automotive", description: "Vehicle & parts logistics", href: "/industries/automotive" },
          { icon: Pill, title: "Pharmaceutical", description: "Temperature-controlled pharma", href: "/industries/pharmaceutical" },
          { icon: Cpu, title: "Technology", description: "Secure high-value tech", href: "/industries/technology" },
        ],
      },
      {
        title: "More Industries",
        links: [
          { icon: ShoppingBag, title: "Retail & E-Commerce", description: "Fast fulfillment & delivery", href: "/industries/retail" },
          { icon: Zap, title: "Energy & Mining", description: "Heavy equipment transport", href: "/industries/energy" },
          { icon: UtensilsCrossed, title: "Food & Beverage", description: "Cold chain logistics", href: "/industries/food-beverage" },
        ],
      },
      {
        title: "Featured",
        featured: {
          image: "/global-network.png",
          title: "Industry Solutions",
          description: "Tailored logistics solutions designed for the unique challenges of your industry.",
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
    const handleScroll = () => setIsScrolled(window.scrollY > 20);
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const closeMobileMenu = () => {
    setIsMobileMenuOpen(false);
    setMobileAccordion(null);
  };

  const isSolid = !isHome || isScrolled || isMobileMenuOpen;

  return (
    <header
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${
        isSolid
          ? "bg-white/90 backdrop-blur-xl shadow-[0_1px_3px_rgba(0,0,0,0.05),0_20px_50px_rgba(0,61,102,0.08)] border-b border-white/20"
          : "bg-transparent"
      }`}
    >
      {/* Top Bar - visible on scroll */}
      <AnimatePresence>
        {isSolid && (
          <motion.div
            initial={{ height: 0, opacity: 0 }}
            animate={{ height: "auto", opacity: 1 }}
            exit={{ height: 0, opacity: 0 }}
            transition={{ duration: 0.3 }}
            className="overflow-hidden bg-[#003D66] text-white"
          >
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between py-2 text-xs">
              <div className="flex items-center gap-6">
                <a href="tel:+18005571234" className="flex items-center gap-1.5 hover:text-[#FFA040] transition-colors">
                  <Phone size={12} />
                  <span>+1 (800) 557-1234</span>
                </a>
                <span className="hidden sm:flex items-center gap-1.5">
                  <Navigation size={12} />
                  <span>150+ Countries</span>
                </span>
              </div>
              <div className="flex items-center gap-4">
                <span className="hidden sm:inline text-white/60">24/7 Support</span>
                <Link href="/tracking" className="hover:text-[#FFA040] transition-colors font-medium">
                  Track Shipment
                </Link>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <Link href="/" className="flex items-center gap-3 group">
            <div className="relative">
              <img
                src="/logo.jpeg"
                alt="Sky Port Logistics"
                className="h-14 w-14 rounded-xl object-cover ring-2 ring-[#FF7F00]/50 group-hover:ring-[#FF7F00] transition-all shadow-lg"
              />
              <div className="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-[#FF7F00] rounded-full border-2 border-white flex items-center justify-center">
                <div className="w-1 h-1 bg-white rounded-full" />
              </div>
            </div>
            <div>
              <span className={`text-2xl font-extrabold tracking-tight transition-colors duration-300 ${isSolid ? "text-[#003D66]" : "text-white"}`}>
                SKY<span className="text-[#FF7F00]">PORT</span>
              </span>
              <span className={`block text-[10px] tracking-[0.35em] uppercase font-medium transition-colors duration-300 ${isSolid ? "text-[#005B96]/60" : "text-white/50"}`}>
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
                    className={`relative px-4 py-2 text-[13px] font-semibold tracking-wide transition-colors rounded-lg group ${
                      pathname === item.href
                        ? "text-[#FF7F00]"
                        : isSolid ? "text-[#003D66] hover:text-[#FF7F00]" : "text-white/90 hover:text-white"
                    }`}
                  >
                    {item.label}
                    {pathname === item.href && (
                      <motion.div
                        layoutId="nav-indicator"
                        className="absolute bottom-0 left-2 right-2 h-0.5 bg-[#FF7F00] rounded-full"
                      />
                    )}
                    <span className="absolute bottom-0 left-2 right-2 h-0.5 bg-[#FF7F00]/0 group-hover:bg-[#FF7F00] rounded-full transition-all duration-300" />
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
                    className={`px-4 py-2 text-[13px] font-semibold tracking-wide transition-colors rounded-lg flex items-center gap-1.5 ${
                      pathname.startsWith(item.href)
                        ? "text-[#FF7F00]"
                        : isSolid ? "text-[#003D66] hover:text-[#FF7F00]" : "text-white/90 hover:text-white"
                    }`}
                  >
                    {item.label}
                    <ChevronDown
                      size={12}
                      className={`transition-transform duration-300 ${activeDropdown === item.label ? "rotate-180" : ""}`}
                    />
                  </Link>

                  {/* Dropdown / Mega Menu */}
                  <AnimatePresence>
                    {activeDropdown === item.label && (
                      <motion.div
                        initial={{ opacity: 0, y: 12, scale: 0.98 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: 12, scale: 0.98 }}
                        transition={{ duration: 0.2, ease: [0.4, 0, 0.2, 1] }}
                        className={
                          item.type === "mega"
                            ? "absolute left-1/2 -translate-x-1/2 top-full pt-3"
                            : "absolute left-0 top-full pt-3"
                        }
                      >
                        {/* Arrow */}
                        <div className={`w-4 h-4 bg-white rotate-45 shadow-sm border-l border-t border-gray-100 mx-auto mb-[-8px] ${item.type === "mega" ? "" : "ml-6"}`} />

                        {item.type === "mega" && item.columns ? (
                          <div className="bg-white rounded-2xl shadow-[0_25px_60px_-12px_rgba(0,61,102,0.15)] border border-gray-100/80 p-6 w-[740px]">
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
                                        className="w-full h-full object-cover min-h-[260px] group-hover:scale-105 transition-transform duration-700"
                                      />
                                      <div className="absolute inset-0 bg-gradient-to-t from-[#003D66] via-[#003D66]/60 to-transparent" />
                                      <div className="absolute inset-0 p-5 flex flex-col justify-end">
                                        <h4 className="text-white font-bold text-lg mb-2">
                                          {col.featured.title}
                                        </h4>
                                        <p className="text-white/70 text-sm mb-4 leading-relaxed">
                                          {col.featured.description}
                                        </p>
                                        <Link
                                          href={col.featured.cta.href}
                                          className="inline-flex items-center gap-1.5 text-[#FF7F00] text-sm font-semibold hover:text-[#FFA040] transition-colors"
                                        >
                                          {col.featured.cta.text}
                                          <ArrowRight size={14} className="group-hover:translate-x-1 transition-transform" />
                                        </Link>
                                      </div>
                                    </div>
                                  );
                                }

                                return (
                                  <div key={colIdx}>
                                    {col.title && (
                                      <h4 className="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 pb-2 border-b border-gray-100">
                                        {col.title}
                                      </h4>
                                    )}
                                    <div className="space-y-0.5">
                                      {col.links?.map((link) => {
                                        const Icon = link.icon;
                                        return (
                                          <Link
                                            key={link.href}
                                            href={link.href}
                                            className="flex items-start gap-3 p-2.5 rounded-xl hover:bg-gradient-to-r hover:from-[#005B96]/5 hover:to-transparent transition-all duration-200 group"
                                          >
                                            <div className="bg-[#005B96]/8 rounded-lg p-2 mt-0.5 group-hover:bg-[#FF7F00]/10 transition-colors duration-200">
                                              <Icon className="text-[#005B96] group-hover:text-[#FF7F00] transition-colors duration-200" size={16} />
                                            </div>
                                            <div>
                                              <div className="font-semibold text-[#003D66] text-sm group-hover:text-[#FF7F00] transition-colors duration-200">
                                                {link.title}
                                              </div>
                                              <div className="text-gray-400 text-xs mt-0.5">
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
                          <div className="bg-white rounded-xl shadow-[0_25px_60px_-12px_rgba(0,61,102,0.15)] border border-gray-100/80 p-1.5 min-w-[220px]">
                            {item.links?.map((link) => {
                              const Icon = link.icon;
                              return (
                                <Link
                                  key={link.href}
                                  href={link.href}
                                  className="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gradient-to-r hover:from-[#005B96]/5 hover:to-transparent transition-all duration-200 group"
                                >
                                  <Icon className="text-[#005B96] group-hover:text-[#FF7F00] transition-colors duration-200" size={16} />
                                  <span className="font-medium text-[#003D66] text-sm group-hover:text-[#FF7F00] transition-colors duration-200">
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

            <div className="ml-4 flex items-center gap-3">
              <Link href="/tracking">
                <Button variant="ghost" className={`text-[13px] font-semibold rounded-full px-4 ${isSolid ? "text-[#005B96] hover:text-[#FF7F00] hover:bg-[#FF7F00]/5" : "text-white/80 hover:text-white hover:bg-white/10"}`}>
                  Track
                </Button>
              </Link>
              <Link href="/contact">
                <Button className="bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold px-6 rounded-full shadow-lg shadow-[#FF7F00]/25 hover:shadow-[#FF7F00]/40 transition-all btn-glow h-10 text-[13px]">
                  Get a Quote
                </Button>
              </Link>
            </div>
          </nav>

          {/* Mobile Menu Toggle */}
          <button
            className="lg:hidden relative w-10 h-10 flex items-center justify-center rounded-xl transition-colors"
            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
            aria-label="Toggle menu"
          >
            <AnimatePresence mode="wait">
              {isMobileMenuOpen ? (
                <motion.div key="close" initial={{ rotate: -90, opacity: 0 }} animate={{ rotate: 0, opacity: 1 }} exit={{ rotate: 90, opacity: 0 }} transition={{ duration: 0.2 }}>
                  <X className={isSolid ? "text-[#003D66]" : "text-white"} size={24} />
                </motion.div>
              ) : (
                <motion.div key="menu" initial={{ rotate: 90, opacity: 0 }} animate={{ rotate: 0, opacity: 1 }} exit={{ rotate: -90, opacity: 0 }} transition={{ duration: 0.2 }}>
                  <Menu className={isSolid ? "text-[#003D66]" : "text-white"} size={24} />
                </motion.div>
              )}
            </AnimatePresence>
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
            transition={{ duration: 0.3 }}
            className="lg:hidden bg-white border-t border-gray-100 shadow-2xl"
          >
            <div className="max-h-[80vh] overflow-y-auto px-4 py-6 space-y-1">
              {navItems.map((item, idx) => {
                if (item.type === "link") {
                  return (
                    <motion.div
                      key={item.label}
                      initial={{ opacity: 0, x: -20 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ delay: idx * 0.05 }}
                    >
                      <Link
                        href={item.href}
                        onClick={closeMobileMenu}
                        className="block text-[#003D66] font-semibold py-3.5 px-4 hover:text-[#FF7F00] hover:bg-[#FF7F00]/5 rounded-xl transition-all"
                      >
                        {item.label}
                      </Link>
                    </motion.div>
                  );
                }

                return (
                  <motion.div
                    key={item.label}
                    initial={{ opacity: 0, x: -20 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: idx * 0.05 }}
                  >
                    <button
                      onClick={() => setMobileAccordion(mobileAccordion === item.label ? null : item.label)}
                      className="w-full flex items-center justify-between text-[#003D66] font-semibold py-3.5 px-4 hover:text-[#FF7F00] hover:bg-[#FF7F00]/5 rounded-xl transition-all"
                    >
                      {item.label}
                      <ChevronDown size={16} className={`transition-transform duration-300 ${mobileAccordion === item.label ? "rotate-180" : ""}`} />
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
                          <div className="pl-4 pb-2 space-y-0.5">
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
                                    className="flex items-center gap-3 py-2.5 px-3 text-sm text-gray-600 hover:text-[#FF7F00] hover:bg-[#FF7F00]/5 rounded-lg transition-all"
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
                              className="flex items-center gap-2 py-2.5 px-3 text-sm font-semibold text-[#FF7F00] hover:bg-[#FF7F00]/5 rounded-lg transition-all"
                            >
                              View All
                              <ArrowRight size={14} />
                            </Link>
                          </div>
                        </motion.div>
                      )}
                    </AnimatePresence>
                  </motion.div>
                );
              })}
              <div className="pt-4 space-y-3">
                <Link href="/tracking" onClick={closeMobileMenu}>
                  <Button variant="outline" className="w-full border-[#005B96]/20 text-[#005B96] font-semibold rounded-xl h-12 hover:bg-[#005B96]/5">
                    Track Shipment
                  </Button>
                </Link>
                <Link href="/contact" onClick={closeMobileMenu}>
                  <Button className="w-full bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold rounded-xl h-12 btn-glow">
                    Get a Quote
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
