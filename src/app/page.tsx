"use client";

import { useState, useEffect, useRef } from "react";
import { motion, useInView, AnimatePresence } from "framer-motion";
import {
  Plane,
  Ship,
  Truck,
  Warehouse,
  Globe,
  Shield,
  Clock,
  HeadphonesIcon,
  Package,
  ArrowRight,
  MapPin,
  Phone,
  Mail,
  Menu,
  X,
  ChevronRight,
  CheckCircle,
  BarChart3,
  Zap,
  Users,
  Star,
  Send,
  Navigation,
  Anchor,
  Container,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";

// Animated counter hook
function useCounter(target: number, duration: number = 2000, start: boolean = false) {
  const [count, setCount] = useState(0);
  useEffect(() => {
    if (!start) return;
    let startTime: number | null = null;
    const step = (timestamp: number) => {
      if (!startTime) startTime = timestamp;
      const progress = Math.min((timestamp - startTime) / duration, 1);
      setCount(Math.floor(progress * target));
      if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  }, [target, duration, start]);
  return count;
}

// Section wrapper with scroll animation
function AnimatedSection({
  children,
  className = "",
  delay = 0,
}: {
  children: React.ReactNode;
  className?: string;
  delay?: number;
}) {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-100px" });

  return (
    <motion.div
      ref={ref}
      initial={{ opacity: 0, y: 40 }}
      animate={isInView ? { opacity: 1, y: 0 } : { opacity: 0, y: 40 }}
      transition={{ duration: 0.7, delay, ease: "easeOut" }}
      className={className}
    >
      {children}
    </motion.div>
  );
}

// Navbar
function Navbar() {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 50);
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const navLinks = [
    { label: "Home", href: "#home" },
    { label: "Services", href: "#services" },
    { label: "About", href: "#about" },
    { label: "Network", href: "#network" },
    { label: "Testimonials", href: "#testimonials" },
    { label: "Contact", href: "#contact" },
  ];

  return (
    <nav
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isScrolled
          ? "bg-white/95 backdrop-blur-md shadow-lg"
          : "bg-transparent"
      }`}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <a href="#home" className="flex items-center gap-3">
            <img
              src="/logo.jpeg"
              alt="Sky Port Logistics"
              className="h-12 w-12 rounded-full object-cover ring-2 ring-[#FF7F00]"
            />
            <div>
              <span
                className={`text-xl font-bold tracking-tight ${
                  isScrolled ? "text-[#005B96]" : "text-white"
                }`}
              >
                SKY PORT
              </span>
              <span
                className={`block text-[10px] tracking-[0.3em] uppercase ${
                  isScrolled ? "text-[#FF7F00]" : "text-[#FFA040]"
                }`}
              >
                Logistics
              </span>
            </div>
          </a>

          {/* Desktop Nav */}
          <div className="hidden md:flex items-center gap-8">
            {navLinks.map((link) => (
              <a
                key={link.label}
                href={link.href}
                className={`text-sm font-medium transition-colors hover:text-[#FF7F00] ${
                  isScrolled ? "text-[#005B96]" : "text-white"
                }`}
              >
                {link.label}
              </a>
            ))}
            <a href="#contact">
              <Button className="bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold px-6 rounded-full shadow-lg hover:shadow-xl transition-all">
                Get Quote
              </Button>
            </a>
          </div>

          {/* Mobile Menu Toggle */}
          <button
            className="md:hidden"
            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
          >
            {isMobileMenuOpen ? (
              <X className={isScrolled ? "text-[#005B96]" : "text-white"} size={28} />
            ) : (
              <Menu className={isScrolled ? "text-[#005B96]" : "text-white"} size={28} />
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
            className="md:hidden bg-white shadow-xl border-t"
          >
            <div className="px-4 py-6 space-y-4">
              {navLinks.map((link) => (
                <a
                  key={link.label}
                  href={link.href}
                  className="block text-[#005B96] font-medium py-2 hover:text-[#FF7F00] transition-colors"
                  onClick={() => setIsMobileMenuOpen(false)}
                >
                  {link.label}
                </a>
              ))}
              <a href="#contact" onClick={() => setIsMobileMenuOpen(false)}>
                <Button className="w-full bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold rounded-full">
                  Get Quote
                </Button>
              </a>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </nav>
  );
}

// Hero Section
function HeroSection() {
  return (
    <section
      id="home"
      className="relative min-h-screen flex items-center overflow-hidden"
    >
      {/* Background */}
      <div className="absolute inset-0">
        <img
          src="/hero-bg.png"
          alt="Cargo port aerial view"
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-r from-[#005B96]/90 via-[#003D66]/80 to-[#005B96]/60" />
      </div>

      {/* Animated particles/decorations */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <motion.div
          className="absolute top-20 right-20 w-64 h-64 rounded-full bg-[#FF7F00]/10 blur-3xl"
          animate={{ scale: [1, 1.2, 1], opacity: [0.3, 0.5, 0.3] }}
          transition={{ duration: 4, repeat: Infinity }}
        />
        <motion.div
          className="absolute bottom-20 left-10 w-48 h-48 rounded-full bg-[#FF7F00]/10 blur-3xl"
          animate={{ scale: [1, 1.3, 1], opacity: [0.2, 0.4, 0.2] }}
          transition={{ duration: 5, repeat: Infinity }}
        />
      </div>

      {/* Content */}
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <div className="max-w-3xl">
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8 }}
          >
            <span className="inline-flex items-center gap-2 bg-[#FF7F00]/20 text-[#FFA040] border border-[#FF7F00]/30 rounded-full px-4 py-2 text-sm font-medium mb-6">
              <Zap size={16} />
              #1 Global Logistics Partner
            </span>
          </motion.div>

          <motion.h1
            className="text-4xl sm:text-5xl lg:text-7xl font-bold text-white leading-tight mb-6"
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
          >
            Your Gateway to{" "}
            <span className="text-[#FF7F00]">Global Shipping</span>{" "}
            Excellence
          </motion.h1>

          <motion.p
            className="text-lg sm:text-xl text-white/80 mb-8 max-w-2xl leading-relaxed"
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.4 }}
          >
            Sky Port Logistics delivers seamless freight solutions across air,
            ocean, and road. From warehouse to doorstep, we connect your
            business to the world with unmatched speed and reliability.
          </motion.p>

          <motion.div
            className="flex flex-col sm:flex-row gap-4"
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.6 }}
          >
            <a href="#services">
              <Button
                size="lg"
                className="bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold px-8 py-6 text-lg rounded-full shadow-xl hover:shadow-2xl transition-all group"
              >
                Our Services
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
              </Button>
            </a>
            <a href="#tracking">
              <Button
                size="lg"
                variant="outline"
                className="border-white/30 text-white hover:bg-white/10 font-semibold px-8 py-6 text-lg rounded-full backdrop-blur-sm"
              >
                Track Shipment
                <Navigation className="ml-2" />
              </Button>
            </a>
          </motion.div>

          {/* Trust badges */}
          <motion.div
            className="mt-12 flex flex-wrap items-center gap-6"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.8, delay: 0.8 }}
          >
            <div className="flex items-center gap-2 text-white/70">
              <Shield className="text-[#FF7F00]" size={20} />
              <span className="text-sm">ISO 9001 Certified</span>
            </div>
            <div className="flex items-center gap-2 text-white/70">
              <Clock className="text-[#FF7F00]" size={20} />
              <span className="text-sm">24/7 Support</span>
            </div>
            <div className="flex items-center gap-2 text-white/70">
              <Globe className="text-[#FF7F00]" size={20} />
              <span className="text-sm">150+ Countries</span>
            </div>
          </motion.div>
        </div>
      </div>

      {/* Scroll indicator */}
      <motion.div
        className="absolute bottom-8 left-1/2 -translate-x-1/2"
        animate={{ y: [0, 10, 0] }}
        transition={{ duration: 2, repeat: Infinity }}
      >
        <div className="w-8 h-12 rounded-full border-2 border-white/30 flex justify-center pt-2">
          <div className="w-1.5 h-3 bg-white/60 rounded-full" />
        </div>
      </motion.div>
    </section>
  );
}

// Services Section
function ServicesSection() {
  const services = [
    {
      icon: Plane,
      title: "Air Freight",
      description:
        "Express air cargo services with guaranteed delivery timelines. We partner with leading airlines to provide priority boarding, charter services, and temperature-controlled transport for time-sensitive and high-value shipments across all continents.",
      features: ["Same-day delivery options", "Temperature-controlled cargo", "Charter flight services"],
      image: "/cargo-plane.png",
      color: "from-sky-500 to-blue-600",
    },
    {
      icon: Ship,
      title: "Ocean Freight",
      description:
        "Comprehensive ocean shipping solutions for full container loads (FCL) and less-than-container loads (LCL). Our global partnerships with major carriers ensure competitive rates, flexible scheduling, and reliable transit times for every trade lane.",
      features: ["FCL & LCL options", "Competitive global rates", "Refrigerated containers"],
      image: "/cargo-ship.png",
      color: "from-blue-600 to-indigo-700",
    },
    {
      icon: Truck,
      title: "Road Transport",
      description:
        "Efficient road freight and last-mile delivery solutions connecting ports, warehouses, and final destinations. Our fleet includes refrigerated trucks, flatbeds, and specialized vehicles to handle any cargo type with real-time GPS tracking.",
      features: ["GPS-tracked fleet", "Refrigerated transport", "Cross-border solutions"],
      image: "/warehouse.png",
      color: "from-[#005B96] to-[#003D66]",
    },
    {
      icon: Warehouse,
      title: "Warehousing",
      description:
        "State-of-the-art warehousing and distribution centers strategically located near major ports and airports. We offer inventory management, order fulfillment, pick-and-pack services, and bonded warehouse facilities with 24/7 security monitoring.",
      features: ["Inventory management", "Bonded facilities", "24/7 security"],
      image: "/warehouse.png",
      color: "from-[#FF7F00] to-[#CC6600]",
    },
  ];

  return (
    <section id="services" className="py-24 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-16">
          <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
            What We Offer
          </span>
          <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
            Comprehensive Logistics Solutions
          </h2>
          <p className="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
            From air to ocean, road to warehouse — we provide end-to-end supply
            chain solutions tailored to your business needs.
          </p>
        </AnimatedSection>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {services.map((service, idx) => (
            <AnimatedSection key={service.title} delay={idx * 0.15}>
              <Card className="group overflow-hidden border-0 shadow-lg hover:shadow-2xl transition-all duration-500 h-full">
                <div className="relative h-48 overflow-hidden">
                  <img
                    src={service.image}
                    alt={service.title}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                  />
                  <div
                    className={`absolute inset-0 bg-gradient-to-t ${service.color} opacity-70`}
                  />
                  <div className="absolute inset-0 flex items-center justify-center">
                    <div className="bg-white/20 backdrop-blur-sm rounded-full p-4">
                      <service.icon className="text-white" size={40} />
                    </div>
                  </div>
                </div>
                <CardContent className="p-6">
                  <h3 className="text-xl font-bold text-[#005B96] mb-3">
                    {service.title}
                  </h3>
                  <p className="text-gray-600 leading-relaxed mb-4">
                    {service.description}
                  </p>
                  <ul className="space-y-2">
                    {service.features.map((feature) => (
                      <li
                        key={feature}
                        className="flex items-center gap-2 text-sm text-gray-500"
                      >
                        <CheckCircle className="text-[#FF7F00] flex-shrink-0" size={16} />
                        {feature}
                      </li>
                    ))}
                  </ul>
                </CardContent>
              </Card>
            </AnimatedSection>
          ))}
        </div>
      </div>
    </section>
  );
}

// Stat Item Component (separate so hooks are at top level)
function StatItem({
  target,
  suffix,
  label,
  icon: Icon,
  isInView,
  delay,
}: {
  target: number;
  suffix: string;
  label: string;
  icon: React.ComponentType<{ className?: string; size?: number }>;
  isInView: boolean;
  delay: number;
}) {
  const count = useCounter(target, 2000, isInView);
  return (
    <AnimatedSection delay={delay}>
      <div className="text-center">
        <div className="bg-[#FF7F00]/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
          <Icon className="text-[#FF7F00]" size={28} />
        </div>
        <div className="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
          {count.toLocaleString()}
          {suffix}
        </div>
        <div className="text-white/70 mt-2 text-sm sm:text-base">{label}</div>
      </div>
    </AnimatedSection>
  );
}

// Stats Section
function StatsSection() {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true });

  const stats = [
    { target: 150, suffix: "+", label: "Countries Served", icon: Globe },
    { target: 50000, suffix: "+", label: "Shipments/Year", icon: Package },
    { target: 99, suffix: "%", label: "On-Time Delivery", icon: Clock },
    { target: 2500, suffix: "+", label: "Happy Clients", icon: Users },
  ];

  return (
    <section className="relative py-20 overflow-hidden" ref={ref}>
      <div className="absolute inset-0 bg-[#005B96]" />
      <div
        className="absolute inset-0 opacity-10"
        style={{
          backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
        }}
      />
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-8">
          {stats.map((stat, idx) => (
            <StatItem
              key={stat.label}
              target={stat.target}
              suffix={stat.suffix}
              label={stat.label}
              icon={stat.icon}
              isInView={isInView}
              delay={idx * 0.1}
            />
          ))}
        </div>
      </div>
    </section>
  );
}

// About Section
function AboutSection() {
  return (
    <section id="about" className="py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <AnimatedSection>
            <div className="relative">
              <img
                src="/warehouse.png"
                alt="Sky Port Logistics Operations"
                className="rounded-2xl shadow-2xl w-full h-auto"
              />
              <div className="absolute -bottom-6 -right-6 bg-[#FF7F00] text-white rounded-2xl p-6 shadow-xl hidden sm:block">
                <div className="text-3xl font-bold">25+</div>
                <div className="text-sm">Years Experience</div>
              </div>
              <div className="absolute -top-4 -left-4 bg-[#005B96] text-white rounded-xl p-4 shadow-xl hidden sm:block">
                <Anchor className="text-[#FF7F00]" size={32} />
              </div>
            </div>
          </AnimatedSection>

          <AnimatedSection delay={0.2}>
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              About Us
            </span>
            <h2 className="text-3xl sm:text-4xl font-bold text-[#005B96] mt-3 mb-6">
              Delivering Trust, Connecting the World
            </h2>
            <p className="text-gray-600 leading-relaxed mb-4">
              Founded over 25 years ago, Sky Port Logistics has grown from a
              regional freight forwarder into a global logistics powerhouse. Our
              mission is simple: to make international shipping seamless,
              transparent, and reliable for businesses of every size.
            </p>
            <p className="text-gray-600 leading-relaxed mb-6">
              With strategically located offices across six continents, a fleet
              of trusted carrier partners, and proprietary tracking technology,
              we ensure your cargo moves efficiently through every link of the
              supply chain. Our dedicated teams work around the clock to
              anticipate challenges, clear customs swiftly, and deliver on our
              promises — every single time.
            </p>

            <div className="grid grid-cols-2 gap-4 mb-8">
              {[
                { icon: Shield, text: "Fully Insured Cargo" },
                { icon: Clock, text: "Real-Time Tracking" },
                { icon: Globe, text: "Global Network" },
                { icon: HeadphonesIcon, text: "24/7 Dedicated Support" },
              ].map((item) => (
                <div key={item.text} className="flex items-center gap-3">
                  <div className="bg-[#005B96]/10 rounded-lg p-2">
                    <item.icon className="text-[#005B96]" size={20} />
                  </div>
                  <span className="text-sm font-medium text-gray-700">
                    {item.text}
                  </span>
                </div>
              ))}
            </div>

            <a href="#contact">
              <Button className="bg-[#005B96] hover:bg-[#003D66] text-white font-semibold px-8 py-6 rounded-full shadow-lg hover:shadow-xl transition-all group">
                Learn More About Us
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
              </Button>
            </a>
          </AnimatedSection>
        </div>
      </div>
    </section>
  );
}

// Why Choose Us
function WhyChooseUs() {
  const reasons = [
    {
      icon: Zap,
      title: "Lightning Fast",
      description:
        "Our optimized routes and priority carrier partnerships mean your shipments arrive faster. Same-day air freight and express ocean services available for urgent deadlines.",
    },
    {
      icon: Shield,
      title: "Secure & Insured",
      description:
        "Every shipment is covered by comprehensive cargo insurance. Our state-of-the-art monitoring systems and tamper-proof seals ensure your goods stay protected throughout transit.",
    },
    {
      icon: BarChart3,
      title: "Cost Effective",
      description:
        "Leverage our volume-based carrier negotiations and smart routing algorithms to get the most competitive rates without compromising on service quality or delivery speed.",
    },
    {
      icon: HeadphonesIcon,
      title: "Dedicated Support",
      description:
        "A personal account manager and round-the-clock customer service team ensure you always have an expert to turn to. Multi-language support across all major time zones.",
    },
    {
      icon: Container,
      title: "End-to-End Solutions",
      description:
        "From pickup to final delivery, customs clearance to warehousing, we handle every step of the logistics chain so you can focus on growing your business with complete peace of mind.",
    },
    {
      icon: Globe,
      title: "Global Reach",
      description:
        "With offices and partners in over 150 countries, we navigate local regulations, cultural nuances, and infrastructure challenges to deliver your cargo anywhere on the planet.",
    },
  ];

  return (
    <section className="py-24 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-16">
          <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
            Why Sky Port
          </span>
          <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
            Why Businesses Choose Us
          </h2>
          <p className="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
            We combine speed, security, and savings to deliver a logistics
            experience that sets your supply chain apart.
          </p>
        </AnimatedSection>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          {reasons.map((reason, idx) => (
            <AnimatedSection key={reason.title} delay={idx * 0.1}>
              <Card className="group border-0 shadow-md hover:shadow-xl transition-all duration-300 h-full bg-white">
                <CardContent className="p-6">
                  <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-14 h-14 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <reason.icon className="text-white" size={26} />
                  </div>
                  <h3 className="text-lg font-bold text-[#005B96] mb-3">
                    {reason.title}
                  </h3>
                  <p className="text-gray-600 leading-relaxed">
                    {reason.description}
                  </p>
                </CardContent>
              </Card>
            </AnimatedSection>
          ))}
        </div>
      </div>
    </section>
  );
}

// Tracking CTA Section
function TrackingSection() {
  const [trackingId, setTrackingId] = useState("");

  return (
    <section id="tracking" className="py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection>
          <div className="relative overflow-hidden rounded-3xl">
            <div className="absolute inset-0 bg-gradient-to-r from-[#005B96] to-[#003D66]" />
            <div
              className="absolute inset-0 opacity-5"
              style={{
                backgroundImage: `url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
              }}
            />
            <div className="relative z-10 px-6 sm:px-12 py-16 text-center">
              <Package className="text-[#FF7F00] mx-auto mb-6" size={48} />
              <h2 className="text-3xl sm:text-4xl font-bold text-white mb-4">
                Track Your Shipment
              </h2>
              <p className="text-white/80 max-w-lg mx-auto mb-8 text-lg">
                Enter your tracking number to get real-time updates on your
                shipment&apos;s location, status, and estimated delivery time.
              </p>
              <div className="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
                <Input
                  placeholder="Enter tracking number (e.g., SKP-2026-XXXXX)"
                  value={trackingId}
                  onChange={(e) => setTrackingId(e.target.value)}
                  className="flex-1 h-14 bg-white/10 border-white/20 text-white placeholder:text-white/50 text-lg rounded-full px-6 focus:ring-[#FF7F00]"
                />
                <Button className="h-14 bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold px-8 rounded-full shadow-lg text-lg group">
                  Track Now
                  <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
                </Button>
              </div>
            </div>
          </div>
        </AnimatedSection>
      </div>
    </section>
  );
}

// Global Network Section
function NetworkSection() {
  return (
    <section id="network" className="py-24 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-16">
          <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
            Our Reach
          </span>
          <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
            Global Network, Local Expertise
          </h2>
          <p className="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
            With strategically placed hubs on every continent, we navigate
            local regulations and infrastructure to deliver seamless logistics
            anywhere in the world.
          </p>
        </AnimatedSection>

        <AnimatedSection>
          <div className="relative rounded-3xl overflow-hidden shadow-2xl">
            <img
              src="/global-network.png"
              alt="Global logistics network"
              className="w-full h-auto"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-[#003D66]/80 via-transparent to-transparent" />
            <div className="absolute bottom-0 left-0 right-0 p-8 sm:p-12">
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-6">
                {[
                  { region: "Americas", offices: "12 offices" },
                  { region: "Europe", offices: "18 offices" },
                  { region: "Asia Pacific", offices: "24 offices" },
                  { region: "Middle East & Africa", offices: "10 offices" },
                ].map((item) => (
                  <div key={item.region} className="text-white">
                    <div className="text-[#FF7F00] font-bold text-lg">
                      {item.region}
                    </div>
                    <div className="text-white/80 text-sm">{item.offices}</div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </AnimatedSection>
      </div>
    </section>
  );
}

// Testimonials
function TestimonialsSection() {
  const testimonials = [
    {
      name: "Sarah Chen",
      role: "Supply Chain Director, TechVision Inc.",
      text: "Sky Port Logistics transformed our international supply chain. Their air freight services cut our delivery times by 40%, and the real-time tracking gives us complete visibility. The dedicated account manager is always just a call away.",
      rating: 5,
    },
    {
      name: "Marcus Rodriguez",
      role: "CEO, FreshHarvest Exports",
      text: "We ship perishable goods across three continents, and Sky Port's temperature-controlled logistics have been flawless. Zero spoilage in two years of partnership. Their customs expertise has saved us countless hours and headaches.",
      rating: 5,
    },
    {
      name: "Amara Okafor",
      role: "Operations Manager, BuildRight Materials",
      text: "Moving heavy construction materials internationally is complex, but Sky Port makes it feel effortless. Their road transport and warehousing solutions are seamless, and the cost savings compared to our previous provider have been significant.",
      rating: 5,
    },
  ];

  return (
    <section id="testimonials" className="py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-16">
          <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
            Testimonials
          </span>
          <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
            What Our Clients Say
          </h2>
          <p className="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
            Hundreds of businesses trust Sky Port Logistics to move their goods
            across the globe. Here is what some of them have to say.
          </p>
        </AnimatedSection>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {testimonials.map((testimonial, idx) => (
            <AnimatedSection key={testimonial.name} delay={idx * 0.15}>
              <Card className="border-0 shadow-lg hover:shadow-xl transition-all duration-300 h-full">
                <CardContent className="p-6">
                  <div className="flex gap-1 mb-4">
                    {Array.from({ length: testimonial.rating }).map((_, i) => (
                      <Star
                        key={i}
                        className="text-[#FF7F00] fill-[#FF7F00]"
                        size={18}
                      />
                    ))}
                  </div>
                  <p className="text-gray-600 leading-relaxed mb-6 italic">
                    &ldquo;{testimonial.text}&rdquo;
                  </p>
                  <div className="flex items-center gap-3">
                    <div className="bg-[#005B96] text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-sm">
                      {testimonial.name
                        .split(" ")
                        .map((n) => n[0])
                        .join("")}
                    </div>
                    <div>
                      <div className="font-semibold text-[#005B96] text-sm">
                        {testimonial.name}
                      </div>
                      <div className="text-gray-500 text-xs">
                        {testimonial.role}
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </AnimatedSection>
          ))}
        </div>
      </div>
    </section>
  );
}

// CTA Section
function CTASection() {
  return (
    <section className="py-20 bg-gradient-to-r from-[#FF7F00] to-[#CC6600] relative overflow-hidden">
      <div
        className="absolute inset-0 opacity-10"
        style={{
          backgroundImage: `url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='1' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E")`,
        }}
      />
      <div className="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <AnimatedSection>
          <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
            Ready to Ship Smarter?
          </h2>
          <p className="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
            Join thousands of businesses that trust Sky Port Logistics for their
            global shipping needs. Get a free quote today and experience the
            difference.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#contact">
              <Button
                size="lg"
                className="bg-white text-[#FF7F00] hover:bg-gray-100 font-bold px-8 py-6 text-lg rounded-full shadow-xl group"
              >
                Get Free Quote
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
              </Button>
            </a>
            <a href="tel:+18005571234">
              <Button
                size="lg"
                variant="outline"
                className="border-white/30 text-white hover:bg-white/10 font-bold px-8 py-6 text-lg rounded-full"
              >
                <Phone className="mr-2" />
                Call Us Now
              </Button>
            </a>
          </div>
        </AnimatedSection>
      </div>
    </section>
  );
}

// Contact Section
function ContactSection() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    service: "",
    message: "",
  });

  return (
    <section id="contact" className="py-24 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-16">
          <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
            Get In Touch
          </span>
          <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
            Contact Us
          </h2>
          <p className="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
            Have a shipment need or a question? Our team is ready to help you
            find the perfect logistics solution.
          </p>
        </AnimatedSection>

        <div className="grid grid-cols-1 lg:grid-cols-5 gap-12">
          {/* Contact Info */}
          <AnimatedSection className="lg:col-span-2">
            <div className="space-y-6">
              {[
                {
                  icon: MapPin,
                  title: "Head Office",
                  lines: [
                    "Sky Port Tower, 1200 Harbor Blvd",
                    "Suite 800, Los Angeles, CA 90012",
                  ],
                },
                {
                  icon: Phone,
                  title: "Phone",
                  lines: ["+1 (800) 557-1234 (Toll Free)", "+1 (213) 555-6789"],
                },
                {
                  icon: Mail,
                  title: "Email",
                  lines: [
                    "info@skyportlogistics.com",
                    "quotes@skyportlogistics.com",
                  ],
                },
                {
                  icon: Clock,
                  title: "Working Hours",
                  lines: [
                    "Mon – Fri: 8:00 AM – 8:00 PM",
                    "Sat – Sun: Emergency Line Only",
                  ],
                },
              ].map((item) => (
                <div key={item.title} className="flex gap-4">
                  <div className="bg-[#005B96] rounded-xl p-3 h-fit">
                    <item.icon className="text-white" size={22} />
                  </div>
                  <div>
                    <div className="font-semibold text-[#005B96]">
                      {item.title}
                    </div>
                    {item.lines.map((line) => (
                      <div key={line} className="text-gray-600 text-sm">
                        {line}
                      </div>
                    ))}
                  </div>
                </div>
              ))}
            </div>
          </AnimatedSection>

          {/* Contact Form */}
          <AnimatedSection className="lg:col-span-3" delay={0.2}>
            <Card className="border-0 shadow-xl">
              <CardContent className="p-6 sm:p-8">
                <form
                  onSubmit={(e) => {
                    e.preventDefault();
                    alert("Thank you! We will contact you shortly.");
                  }}
                  className="space-y-5"
                >
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                      <label className="text-sm font-medium text-gray-700 mb-1 block">
                        Full Name
                      </label>
                      <Input
                        placeholder="John Doe"
                        value={formData.name}
                        onChange={(e) =>
                          setFormData({ ...formData, name: e.target.value })
                        }
                        className="h-12 rounded-xl"
                      />
                    </div>
                    <div>
                      <label className="text-sm font-medium text-gray-700 mb-1 block">
                        Email Address
                      </label>
                      <Input
                        type="email"
                        placeholder="john@company.com"
                        value={formData.email}
                        onChange={(e) =>
                          setFormData({ ...formData, email: e.target.value })
                        }
                        className="h-12 rounded-xl"
                      />
                    </div>
                  </div>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                      <label className="text-sm font-medium text-gray-700 mb-1 block">
                        Phone Number
                      </label>
                      <Input
                        placeholder="+1 (555) 000-0000"
                        value={formData.phone}
                        onChange={(e) =>
                          setFormData({ ...formData, phone: e.target.value })
                        }
                        className="h-12 rounded-xl"
                      />
                    </div>
                    <div>
                      <label className="text-sm font-medium text-gray-700 mb-1 block">
                        Service Needed
                      </label>
                      <Input
                        placeholder="Air Freight, Ocean, etc."
                        value={formData.service}
                        onChange={(e) =>
                          setFormData({ ...formData, service: e.target.value })
                        }
                        className="h-12 rounded-xl"
                      />
                    </div>
                  </div>
                  <div>
                    <label className="text-sm font-medium text-gray-700 mb-1 block">
                      Message
                    </label>
                    <Textarea
                      placeholder="Tell us about your shipping needs..."
                      rows={4}
                      value={formData.message}
                      onChange={(e) =>
                        setFormData({ ...formData, message: e.target.value })
                      }
                      className="rounded-xl"
                    />
                  </div>
                  <Button
                    type="submit"
                    className="w-full bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold h-12 rounded-full text-lg group"
                  >
                    Send Message
                    <Send className="ml-2 group-hover:translate-x-1 transition-transform" />
                  </Button>
                </form>
              </CardContent>
            </Card>
          </AnimatedSection>
        </div>
      </div>
    </section>
  );
}

// Footer
function Footer() {
  return (
    <footer className="bg-[#003D66] text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
          {/* Brand */}
          <div>
            <div className="flex items-center gap-3 mb-6">
              <img
                src="/logo.jpeg"
                alt="Sky Port Logistics"
                className="h-10 w-10 rounded-full object-cover ring-2 ring-[#FF7F00]"
              />
              <div>
                <span className="text-lg font-bold tracking-tight text-white">
                  SKY PORT
                </span>
                <span className="block text-[10px] tracking-[0.3em] uppercase text-[#FF7F00]">
                  Logistics
                </span>
              </div>
            </div>
            <p className="text-white/60 text-sm leading-relaxed">
              Your trusted partner for global shipping and logistics solutions.
              Connecting businesses to the world with speed, security, and
              reliability since 2001.
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="font-bold text-white mb-4">Quick Links</h4>
            <ul className="space-y-3">
              {["About Us", "Our Services", "Track Shipment", "Careers", "Blog"].map(
                (link) => (
                  <li key={link}>
                    <a
                      href={`#${link.toLowerCase().replace(" ", "-")}`}
                      className="text-white/60 hover:text-[#FF7F00] transition-colors text-sm flex items-center gap-2"
                    >
                      <ChevronRight size={14} />
                      {link}
                    </a>
                  </li>
                )
              )}
            </ul>
          </div>

          {/* Services */}
          <div>
            <h4 className="font-bold text-white mb-4">Our Services</h4>
            <ul className="space-y-3">
              {[
                "Air Freight",
                "Ocean Freight",
                "Road Transport",
                "Warehousing",
                "Customs Clearance",
                "Supply Chain Consulting",
              ].map((service) => (
                <li key={service}>
                  <a
                    href="#services"
                    className="text-white/60 hover:text-[#FF7F00] transition-colors text-sm flex items-center gap-2"
                  >
                    <ChevronRight size={14} />
                    {service}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Newsletter */}
          <div>
            <h4 className="font-bold text-white mb-4">Stay Updated</h4>
            <p className="text-white/60 text-sm mb-4">
              Subscribe to our newsletter for the latest logistics insights and
              industry updates.
            </p>
            <div className="flex gap-2">
              <Input
                placeholder="Your email"
                className="bg-white/10 border-white/20 text-white placeholder:text-white/50 h-10 rounded-full"
              />
              <Button className="bg-[#FF7F00] hover:bg-[#CC6600] text-white rounded-full px-4 h-10">
                <Send size={16} />
              </Button>
            </div>
          </div>
        </div>

        <div className="border-t border-white/10 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p className="text-white/40 text-sm">
            &copy; {new Date().getFullYear()} Sky Port Logistics. All rights
            reserved.
          </p>
          <div className="flex gap-6">
            {["Privacy Policy", "Terms of Service", "Cookie Policy"].map(
              (link) => (
                <a
                  key={link}
                  href="#"
                  className="text-white/40 hover:text-[#FF7F00] transition-colors text-sm"
                >
                  {link}
                </a>
              )
            )}
          </div>
        </div>
      </div>
    </footer>
  );
}

// Main Page
export default function Home() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navbar />
      <main className="flex-1">
        <HeroSection />
        <ServicesSection />
        <StatsSection />
        <AboutSection />
        <WhyChooseUs />
        <TrackingSection />
        <NetworkSection />
        <TestimonialsSection />
        <CTASection />
        <ContactSection />
      </main>
      <Footer />
    </div>
  );
}
