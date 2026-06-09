"use client";

import { useState, useEffect, useRef, useCallback } from "react";
import { motion, useInView, AnimatePresence } from "framer-motion";

import {
  Plane, Ship, Truck, Warehouse, Globe, Shield, Clock, HeadphonesIcon,
  Package, ArrowRight, ChevronRight, ChevronLeft, CheckCircle, BarChart3,
  Zap, Users, Star, Send, Navigation, Anchor, Container, TrendingUp,
  MapPin, Phone, Mail, Play, Award, MoveRight,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import Link from "next/link";
import AnimatedSection from "@/components/AnimatedSection";

// Animated counter hook
function useCounter(target: number, duration: number = 2000, start: boolean = false) {
  const [count, setCount] = useState(0);
  useEffect(() => {
    if (!start) return;
    let startTime: number | null = null;
    const step = (timestamp: number) => {
      if (!startTime) startTime = timestamp;
      const progress = Math.min((timestamp - startTime) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      setCount(Math.floor(eased * target));
      if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  }, [target, duration, start]);
  return count;
}

// Hero Slide Data
const heroSlides = [
  {
    image: "/hero-bg.png",
    gradient: "from-[#003D66]/95 via-[#005B96]/80 to-[#003D66]/60",
    badge: { icon: Zap, text: "#1 Global Logistics Partner" },
    title: ["Your Gateway to", "Global Shipping", "Excellence"],
    description: "Sky Port Logistics delivers seamless freight solutions across air, ocean, and road. From warehouse to doorstep, we connect your business to the world with unmatched speed and reliability.",
    primaryCta: { text: "Explore Services", href: "/services" },
    secondaryCta: { text: "Track Shipment", href: "/tracking", icon: Navigation },
  },
  {
    image: "/hero-ocean.png",
    gradient: "from-[#003D66]/95 via-[#005B96]/85 to-[#003D66]/70",
    badge: { icon: Ship, text: "Ocean Freight Specialists" },
    title: ["Navigate the Seas", "With Confidence", "& Precision"],
    description: "We combine speed, security, and savings to deliver a logistics experience that sets your supply chain apart. Full container or consolidated — your cargo, our commitment.",
    primaryCta: { text: "Ocean Solutions", href: "/services/ocean-freight" },
    secondaryCta: { text: "Get a Quote", href: "/contact", icon: ArrowRight },
  },
  {
    image: "/hero-warehouse.png",
    gradient: "from-[#005B96]/90 via-[#0078C8]/80 to-[#003D66]/80",
    badge: { icon: TrendingUp, text: "Smart Supply Chain Solutions" },
    title: ["Where Speed Meets", "Security & Savings", "Every Time"],
    description: "We combine speed, security, and savings to deliver a logistics experience that sets your supply chain apart. State-of-the-art warehousing, real-time tracking, and end-to-end visibility.",
    primaryCta: { text: "Explore Solutions", href: "/services" },
    secondaryCta: { text: "Track Shipment", href: "/tracking", icon: Navigation },
  },
  {
    image: "/cargo-plane.png",
    gradient: "from-[#003D66]/95 via-[#005B96]/80 to-[#003D66]/65",
    badge: { icon: Plane, text: "Express Air Cargo" },
    title: ["Deliver Faster,", "Fly Higher", "Worldwide"],
    description: "Priority air freight with guaranteed timelines. Temperature-controlled, charter-ready, and connected to every major hub on the planet. Your urgent shipments, delivered on schedule.",
    primaryCta: { text: "Air Freight", href: "/services/air-freight" },
    secondaryCta: { text: "Get a Quote", href: "/contact", icon: ArrowRight },
  },
];

// =================== HERO SECTION ===================
const heroZoomClasses = [
  "hero-slide-img-active",
  "hero-slide-img-right",
  "hero-slide-img-up",
  "hero-slide-img-left",
];

function HeroSection() {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [direction, setDirection] = useState(1);
  const [isPaused, setIsPaused] = useState(false);
  const [progress, setProgress] = useState(0);
  const timerRef = useRef<ReturnType<typeof setTimeout> | null>(null);
  const progressRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const SLIDE_DURATION = 6000;

  // Autoplay with progress tracking
  useEffect(() => {
    if (isPaused) {
      if (progressRef.current) clearInterval(progressRef.current);
      if (timerRef.current) clearTimeout(timerRef.current);
      return;
    }

    setProgress(0);
    const startTime = Date.now();

    progressRef.current = setInterval(() => {
      const elapsed = Date.now() - startTime;
      setProgress(Math.min((elapsed / SLIDE_DURATION) * 100, 100));
    }, 50);

    timerRef.current = setTimeout(() => {
      setDirection(1);
      setCurrentSlide((prev) => (prev + 1) % heroSlides.length);
    }, SLIDE_DURATION);

    return () => {
      if (progressRef.current) clearInterval(progressRef.current);
      if (timerRef.current) clearTimeout(timerRef.current);
    };
  }, [currentSlide, isPaused]);

  const goToSlide = useCallback((index: number) => {
    setDirection(index > currentSlide ? 1 : -1);
    setCurrentSlide(index);
  }, [currentSlide]);

  const goNext = useCallback(() => {
    setDirection(1);
    setCurrentSlide((prev) => (prev + 1) % heroSlides.length);
  }, []);

  const goPrev = useCallback(() => {
    setDirection(-1);
    setCurrentSlide((prev) => (prev - 1 + heroSlides.length) % heroSlides.length);
  }, []);

  return (
    <section
      className="relative min-h-screen flex items-center overflow-hidden"
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
    >
      {/* Background Slides - Crossfade */}
      {heroSlides.map((slide, idx) => (
        <div
          key={idx}
          className={`absolute inset-0 transition-all duration-[1200ms] ease-out ${
            idx === currentSlide ? "opacity-100" : "opacity-0"
          }`}
        >
          <img
            src={slide.image}
            alt={slide.title.join(" ")}
            className={`w-full h-full object-cover ${
              idx === currentSlide ? heroZoomClasses[idx] : ""
            }`}
            style={idx !== currentSlide ? { transform: "scale(1.05)" } : undefined}
          />
          <div className={`absolute inset-0 bg-gradient-to-r ${slide.gradient}`} />
          {/* Vignette overlay for cinematic depth */}
          <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_40%,rgba(0,61,102,0.4)_100%)]" />
          {/* Diagonal light streak */}
          <div className="absolute inset-0 overflow-hidden pointer-events-none">
            <div className="absolute -top-1/2 -right-1/4 w-[120%] h-[200%] bg-gradient-to-bl from-white/[0.04] via-transparent to-transparent rotate-12" />
          </div>
          {/* Animated ambient particles - only on active slide */}
          {idx === currentSlide && (
            <div className="absolute inset-0 overflow-hidden pointer-events-none">
              <motion.div className="absolute top-1/4 right-1/4 w-96 h-96 rounded-full bg-[#FF7F00]/10 blur-[120px]" animate={{ scale: [1, 1.4, 1], opacity: [0.3, 0.6, 0.3] }} transition={{ duration: 6, repeat: Infinity }} />
              <motion.div className="absolute bottom-1/3 left-1/6 w-72 h-72 rounded-full bg-[#FF7F00]/8 blur-[100px]" animate={{ scale: [1, 1.5, 1], opacity: [0.2, 0.45, 0.2] }} transition={{ duration: 8, repeat: Infinity }} />
              <motion.div className="absolute top-1/2 right-1/3 w-56 h-56 rounded-full bg-[#0078C8]/12 blur-[80px]" animate={{ scale: [1, 1.3, 1], x: [0, 40, 0] }} transition={{ duration: 7, repeat: Infinity }} />
            </div>
          )}
        </div>
      ))}

      {/* Sweep Light Effect on slide change */}
      <AnimatePresence>
        <motion.div
          key={`sweep-${currentSlide}`}
          className="absolute inset-0 pointer-events-none"
          style={{ zIndex: 15 }}
          initial={{ x: "-100%" }}
          animate={{ x: "200%" }}
          transition={{ duration: 1.5, ease: [0.25, 0.46, 0.45, 0.94] }}
        >
          <div className="w-1/4 h-full bg-gradient-to-r from-transparent via-white/[0.06] to-transparent skew-x-[-20deg]" />
        </motion.div>
      </AnimatePresence>

      {/* Content Overlay */}
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 w-full" style={{ zIndex: 20 }}>
        <AnimatePresence mode="wait">
          <motion.div
            key={currentSlide}
            className="max-w-3xl"
            initial={{ opacity: 0, x: direction * 80 }}
            animate={{ opacity: 1, x: 0 }}
            exit={{ opacity: 0, x: direction * -40 }}
            transition={{ duration: 0.7, ease: [0.25, 0.46, 0.45, 0.94] }}
          >
            {/* Badge */}
            <motion.div
              initial={{ opacity: 0, y: 20, scale: 0.95 }}
              animate={{ opacity: 1, y: 0, scale: 1 }}
              transition={{ duration: 0.5, delay: 0.15, ease: [0.25, 0.46, 0.45, 0.94] }}
            >
              <span className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white border border-white/15 rounded-full px-5 py-2.5 text-sm font-medium mb-8 shadow-lg">
                {(() => { const Icon = heroSlides[currentSlide].badge.icon; return <Icon size={14} className="text-[#FF7F00]" />; })()}
                {heroSlides[currentSlide].badge.text}
              </span>
            </motion.div>

            {/* Title with staggered line-by-line reveal */}
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.05] mb-8 tracking-tight">
              {heroSlides[currentSlide].title.map((line, lineIdx) => (
                <div key={lineIdx} className="overflow-hidden">
                  <motion.div
                    initial={{ y: "100%", opacity: 0 }}
                    animate={{ y: 0, opacity: 1 }}
                    transition={{ duration: 0.6, delay: 0.25 + lineIdx * 0.12, ease: [0.25, 0.46, 0.45, 0.94] }}
                  >
                    {lineIdx === 1 ? (
                      <span className="bg-gradient-to-r from-[#FF7F00] to-[#FFA040] bg-clip-text text-transparent">{line}</span>
                    ) : (
                      <span>{line}</span>
                    )}
                  </motion.div>
                </div>
              ))}
            </h1>

            {/* Description */}
            <motion.p
              className="text-lg sm:text-xl text-white/75 mb-10 max-w-2xl leading-relaxed"
              initial={{ opacity: 0, y: 25 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: 0.6, ease: [0.25, 0.46, 0.45, 0.94] }}
            >
              {heroSlides[currentSlide].description}
            </motion.p>

            {/* CTAs */}
            <motion.div
              className="flex flex-col sm:flex-row gap-4"
              initial={{ opacity: 0, y: 25 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: 0.7, ease: [0.25, 0.46, 0.45, 0.94] }}
            >
              <Link href={heroSlides[currentSlide].primaryCta.href}>
                <Button size="lg" className="bg-[#FF7F00] hover:bg-[#CC6600] text-white font-bold px-8 py-7 text-base rounded-xl shadow-xl shadow-[#FF7F00]/25 hover:shadow-[#FF7F00]/40 transition-all btn-glow group">
                  {heroSlides[currentSlide].primaryCta.text}
                  <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={18} />
                </Button>
              </Link>
              <Link href={heroSlides[currentSlide].secondaryCta.href}>
                <Button size="lg" variant="outline" className="border-white/20 text-white hover:bg-white/10 font-semibold px-8 py-7 text-base rounded-xl backdrop-blur-md group">
                  {heroSlides[currentSlide].secondaryCta.text}
                  {(() => { const Icon = heroSlides[currentSlide].secondaryCta.icon; return <Icon className="ml-2 group-hover:translate-x-1 transition-transform" size={18} />; })()}
                </Button>
              </Link>
            </motion.div>

            {/* Trust badges */}
            <motion.div
              className="mt-14 flex flex-wrap items-center gap-8"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ duration: 0.5, delay: 0.9 }}
            >
              {[
                { icon: Shield, text: "ISO 9001 Certified" },
                { icon: Clock, text: "24/7 Support" },
                { icon: Globe, text: "150+ Countries" },
              ].map((badge) => (
                <div key={badge.text} className="flex items-center gap-2.5">
                  <div className="w-8 h-8 rounded-lg bg-[#FF7F00]/15 flex items-center justify-center">
                    <badge.icon className="text-[#FF7F00]" size={16} />
                  </div>
                  <span className="text-white/60 text-sm font-medium">{badge.text}</span>
                </div>
              ))}
            </motion.div>
          </motion.div>
        </AnimatePresence>
      </div>

      {/* Vertical side text - company branding */}
      <div className="hidden xl:flex absolute left-6 top-1/2 -translate-y-1/2 flex-col items-center gap-4" style={{ zIndex: 25 }}>
        <div className="w-px h-16 bg-gradient-to-b from-transparent via-white/20 to-transparent" />
        <span className="text-white/25 text-[10px] font-bold tracking-[0.3em] uppercase [writing-mode:vertical-lr] rotate-180">Sky Port Logistics</span>
        <div className="w-px h-16 bg-gradient-to-b from-transparent via-white/20 to-transparent" />
      </div>

      {/* Animated Slide Counter - top right */}
      <div className="hidden lg:flex absolute right-8 xl:right-12 top-28 flex-col items-center gap-2" style={{ zIndex: 25 }}>
        <div className="relative h-12 w-16 overflow-hidden">
          <AnimatePresence mode="popLayout">
            <motion.span
              key={currentSlide}
              className="absolute inset-0 flex items-center justify-center text-white text-3xl font-extrabold tabular-nums"
              initial={{ y: direction > 0 ? -48 : 48, opacity: 0 }}
              animate={{ y: 0, opacity: 1 }}
              exit={{ y: direction > 0 ? 48 : -48, opacity: 0 }}
              transition={{ duration: 0.4, ease: [0.25, 0.46, 0.45, 0.94] }}
            >
              {String(currentSlide + 1).padStart(2, "0")}
            </motion.span>
          </AnimatePresence>
        </div>
        <div className="w-10 h-px bg-gradient-to-r from-transparent via-[#FF7F00]/60 to-transparent" />
        <span className="text-white/35 text-xs font-bold tabular-nums">{String(heroSlides.length).padStart(2, "0")}</span>
        <div className="mt-1 text-[#FF7F00]/35 text-[9px] font-bold tracking-[0.2em] uppercase [writing-mode:vertical-lr] rotate-180">
          {heroSlides[currentSlide].badge.text.split(" ").slice(-1)[0]}
        </div>
      </div>

      {/* Navigation Arrows - premium glass style */}
      <div className="absolute left-3 sm:left-6 lg:left-10 top-1/2 -translate-y-1/2" style={{ zIndex: 25 }}>
        <button onClick={goPrev} className="group w-11 h-11 sm:w-14 sm:h-14 rounded-2xl glass text-white hover:bg-white/15 transition-all duration-300 hover:scale-110 flex items-center justify-center" aria-label="Previous slide">
          <ChevronLeft size={22} className="group-hover:-translate-x-0.5 transition-transform" />
        </button>
      </div>
      <div className="absolute right-3 sm:right-6 lg:right-10 top-1/2 -translate-y-1/2" style={{ zIndex: 25 }}>
        <button onClick={goNext} className="group w-11 h-11 sm:w-14 sm:h-14 rounded-2xl glass text-white hover:bg-white/15 transition-all duration-300 hover:scale-110 flex items-center justify-center" aria-label="Next slide">
          <ChevronRight size={22} className="group-hover:translate-x-0.5 transition-transform" />
        </button>
      </div>

      {/* Bottom Navigation: Slide Thumbnails */}
      <div className="absolute bottom-16 sm:bottom-20 left-1/2 -translate-x-1/2 flex items-center gap-3" style={{ zIndex: 25 }}>
        {heroSlides.map((slide, idx) => (
          <button
            key={idx}
            onClick={() => goToSlide(idx)}
            className={`relative rounded-lg overflow-hidden transition-all duration-500 group/thumb ${
              currentSlide === idx
                ? "w-20 h-12 sm:w-24 sm:h-14 ring-2 ring-[#FF7F00] shadow-lg shadow-[#FF7F00]/20"
                : "w-14 h-9 sm:w-16 sm:h-10 opacity-40 hover:opacity-70 ring-1 ring-white/20 hover:ring-white/40"
            }`}
            aria-label={`Go to slide ${idx + 1}`}
          >
            <img src={slide.image} alt="" className="w-full h-full object-cover" />
            <div className={`absolute inset-0 ${
              currentSlide === idx
                ? "bg-gradient-to-t from-[#FF7F00]/30 to-transparent"
                : "bg-black/30 group-hover/thumb:bg-black/20"
            }`} />
            {/* Progress fill inside active thumbnail */}
            {currentSlide === idx && (
              <motion.div
                className="absolute bottom-0 left-0 h-[3px] bg-[#FF7F00]"
                initial={{ width: "0%" }}
                animate={{ width: `${progress}%` }}
                transition={{ duration: 0.1, ease: "linear" }}
              />
            )}
          </button>
        ))}
      </div>

      {/* Progress bar at bottom */}
      <div className="absolute bottom-0 left-0 right-0 h-1.5 bg-white/5" style={{ zIndex: 25 }}>
        <div
          className="h-full bg-gradient-to-r from-[#FF7F00] to-[#FFA040] shadow-[0_0_10px_rgba(255,127,0,0.4)] transition-all duration-100 ease-linear"
          style={{ width: `${progress}%` }}
        />
      </div>

      {/* Scroll indicator */}
      <motion.div
        className="absolute bottom-6 left-1/2 -translate-x-1/2 hidden sm:block"
        style={{ zIndex: 25 }}
        animate={{ y: [0, 6, 0] }}
        transition={{ duration: 2, repeat: Infinity, ease: "easeInOut" }}
      >
        <div className="w-6 h-9 rounded-full border border-white/15 flex justify-center pt-1.5">
          <motion.div
            className="w-0.5 h-2 bg-white/40 rounded-full"
            animate={{ opacity: [0.4, 1, 0.4], y: [0, 4, 0] }}
            transition={{ duration: 2, repeat: Infinity, ease: "easeInOut" }}
          />
        </div>
      </motion.div>

      {/* Bottom fade for smooth section transition */}
      <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-[#ffffff] via-[#ffffff]/30 to-transparent pointer-events-none" style={{ zIndex: 12 }} />
    </section>
  );
}
// =================== PARTNERS MARQUEE ===================
function PartnersMarquee() {
  const partners = ["MAERSK", "DHL", "FEDEX", "COSCO", "MSC", "CMA CGM", "HAPAG-LLOYD", "EVERGREEN", "ONE", "YANG MING"];
  return (
    <section className="py-10 bg-white border-y border-gray-100 overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <p className="text-center text-xs font-semibold text-gray-400 uppercase tracking-[0.25em]">Trusted by leading carriers worldwide</p>
      </div>
      <div className="relative">
        <div className="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-10" />
        <div className="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-10" />
        <div className="flex animate-marquee">
          {[...partners, ...partners].map((name, idx) => (
            <div key={idx} className="flex-shrink-0 px-10 flex items-center justify-center">
              <span className="text-xl font-bold text-gray-200 tracking-wider whitespace-nowrap">{name}</span>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

// =================== SERVICES SECTION ===================
function ServicesSection() {
  const services = [
    { icon: Plane, title: "Air Freight", description: "Express air cargo services with guaranteed delivery timelines. Priority boarding, charter services, and temperature-controlled transport for time-sensitive shipments across all continents.", features: ["Same-day delivery options", "Temperature-controlled cargo", "Charter flight services"], image: "/cargo-plane.png", href: "/services/air-freight" },
    { icon: Ship, title: "Ocean Freight", description: "Comprehensive ocean shipping solutions for FCL and LCL. Global partnerships with major carriers ensure competitive rates, flexible scheduling, and reliable transit times.", features: ["FCL & LCL options", "Competitive global rates", "Refrigerated containers"], image: "/hero-ocean.png", href: "/services/ocean-freight" },
    { icon: Truck, title: "Road Transport", description: "Efficient road freight and last-mile delivery connecting ports, warehouses, and final destinations. Refrigerated trucks, flatbeds, and specialized vehicles with real-time GPS tracking.", features: ["GPS-tracked fleet", "Refrigerated transport", "Cross-border solutions"], image: "/warehouse.png", href: "/services/road-transport" },
    { icon: Warehouse, title: "Warehousing", description: "State-of-the-art warehousing and distribution centers near major ports and airports. Inventory management, order fulfillment, pick-and-pack, and bonded facilities with 24/7 security.", features: ["Inventory management", "Bonded facilities", "24/7 security"], image: "/hero-warehouse.png", href: "/services/warehousing" },
  ];

  return (
    <section id="services" className="py-28 bg-white relative">
      <div className="absolute inset-0 dot-pattern" />
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-20">
          <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
            <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
            What We Offer
          </span>
          <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
            Comprehensive Logistics<br />
            <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Solutions</span>
          </h2>
          <p className="mt-5 text-gray-500 max-w-2xl mx-auto text-lg leading-relaxed">
            From air to ocean, road to warehouse — we provide end-to-end supply chain solutions tailored to your business needs.
          </p>
        </AnimatedSection>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
          {services.map((service, idx) => (
            <AnimatedSection key={service.title} delay={idx * 0.1}>
              <Link href={service.href}>
                <div className="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-[#005B96]/8 transition-all duration-500 cursor-pointer border border-gray-100/80 card-border-glow h-full">
                  <div className="relative h-52 overflow-hidden">
                    <img src={service.image} alt={service.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                    <div className="absolute inset-0 bg-gradient-to-t from-[#003D66]/80 via-[#003D66]/30 to-transparent" />
                    <div className="absolute top-4 left-4">
                      <div className="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/20">
                        <service.icon className="text-white" size={24} />
                      </div>
                    </div>
                    <div className="absolute bottom-4 left-4 right-4">
                      <h3 className="text-2xl font-bold text-white">{service.title}</h3>
                    </div>
                  </div>
                  <div className="p-6">
                    <p className="text-gray-500 leading-relaxed mb-5 text-[15px]">{service.description}</p>
                    <div className="space-y-2.5 mb-5">
                      {service.features.map((feature) => (
                        <div key={feature} className="flex items-center gap-2.5 text-sm text-gray-600">
                          <CheckCircle className="text-[#FF7F00] flex-shrink-0" size={16} />
                          {feature}
                        </div>
                      ))}
                    </div>
                    <span className="inline-flex items-center gap-2 text-[#FF7F00] font-semibold text-sm group-hover:gap-3 transition-all">
                      Learn More <MoveRight size={16} />
                    </span>
                  </div>
                </div>
              </Link>
            </AnimatedSection>
          ))}
        </div>
      </div>
    </section>
  );
}

// =================== STAT ITEM ===================
function StatItem({ target, suffix, label, icon: Icon, isInView, delay }: { target: number; suffix: string; label: string; icon: React.ComponentType<{ className?: string; size?: number }>; isInView: boolean; delay: number }) {
  const count = useCounter(target, 2000, isInView);
  return (
    <AnimatedSection delay={delay}>
      <div className="text-center group">
        <div className="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center mx-auto mb-5 border border-white/10 group-hover:bg-[#FF7F00]/20 group-hover:border-[#FF7F00]/30 transition-all duration-300">
          <Icon className="text-[#FF7F00]" size={28} />
        </div>
        <div className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight">
          {count.toLocaleString()}{suffix}
        </div>
        <div className="text-white/50 mt-2 text-sm font-medium uppercase tracking-wider">{label}</div>
      </div>
    </AnimatedSection>
  );
}

// =================== STATS SECTION ===================
function StatsSection() {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true });
  const stats = [
    { target: 150, suffix: "+", label: "Countries Served", icon: Globe },
    { target: 50000, suffix: "+", label: "Shipments / Year", icon: Package },
    { target: 99, suffix: "%", label: "On-Time Delivery", icon: Clock },
    { target: 2500, suffix: "+", label: "Happy Clients", icon: Users },
  ];
  return (
    <section className="relative py-28 overflow-hidden" ref={ref}>
      <div className="absolute inset-0 bg-gradient-to-br from-[#003D66] via-[#005B96] to-[#003D66]" />
      <div className="absolute inset-0 grid-pattern opacity-20" />
      {/* Decorative circles */}
      <div className="absolute top-0 right-0 w-96 h-96 bg-[#FF7F00]/5 rounded-full blur-[100px]" />
      <div className="absolute bottom-0 left-0 w-80 h-80 bg-[#0078C8]/10 rounded-full blur-[80px]" />
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-16">
          <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase">
            <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
            By The Numbers
          </span>
          <h2 className="text-3xl sm:text-4xl font-extrabold text-white mt-4 tracking-tight">
            Global Scale, Local Precision
          </h2>
        </AnimatedSection>
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-16">
          {stats.map((stat, idx) => (
            <StatItem key={stat.label} target={stat.target} suffix={stat.suffix} label={stat.label} icon={stat.icon} isInView={isInView} delay={idx * 0.15} />
          ))}
        </div>
      </div>
    </section>
  );
}

// =================== ABOUT SECTION ===================
function AboutSection() {
  return (
    <section id="about" className="py-28 bg-gray-50 relative overflow-hidden">
      <div className="absolute top-0 right-0 w-[500px] h-[500px] bg-[#005B96]/3 rounded-full blur-[120px]" />
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-20 items-center">
          <AnimatedSection>
            <div className="relative">
              <div className="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#005B96]/10">
                <img src="/warehouse.png" alt="Sky Port Logistics Operations" className="w-full h-[480px] object-cover" />
                <div className="absolute inset-0 bg-gradient-to-t from-[#003D66]/40 to-transparent" />
              </div>
              {/* Floating card - experience */}
              <div className="absolute -bottom-8 -right-4 sm:-right-8 bg-gradient-to-br from-[#FF7F00] to-[#CC6600] text-white rounded-2xl p-6 shadow-xl shadow-[#FF7F00]/20 hidden sm:block">
                <div className="text-4xl font-extrabold">25+</div>
                <div className="text-sm font-medium text-white/80">Years Experience</div>
              </div>
              {/* Floating card - anchor */}
              <div className="absolute -top-6 -left-6 bg-white rounded-2xl p-5 shadow-xl shadow-[#005B96]/10 hidden sm:block">
                <Anchor className="text-[#FF7F00]" size={32} />
              </div>
              {/* Decorative line */}
              <div className="absolute -bottom-4 -left-4 w-24 h-24 border-l-2 border-b-2 border-[#FF7F00]/30 rounded-bl-3xl" />
            </div>
          </AnimatedSection>

          <AnimatedSection delay={0.2}>
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              About Us
            </span>
            <h2 className="text-4xl sm:text-5xl font-extrabold text-[#003D66] mt-5 mb-8 tracking-tight leading-tight">
              Delivering Trust,<br />Connecting the World
            </h2>
            <p className="text-gray-500 leading-relaxed mb-5 text-[15px]">
              Founded over 25 years ago, Sky Port Logistics has grown from a regional freight forwarder into a global logistics powerhouse. Our mission is simple: to make international shipping seamless, transparent, and reliable for businesses of every size.
            </p>
            <p className="text-gray-500 leading-relaxed mb-8 text-[15px]">
              With strategically located offices across six continents, a fleet of trusted carrier partners, and proprietary tracking technology, we ensure your cargo moves efficiently through every link of the supply chain.
            </p>

            <div className="grid grid-cols-2 gap-4 mb-10">
              {[
                { icon: Shield, text: "Fully Insured Cargo" },
                { icon: Clock, text: "Real-Time Tracking" },
                { icon: Globe, text: "Global Network" },
                { icon: HeadphonesIcon, text: "24/7 Dedicated Support" },
              ].map((item) => (
                <div key={item.text} className="flex items-center gap-3 bg-white rounded-xl p-3 shadow-sm border border-gray-100/80">
                  <div className="bg-[#005B96]/8 rounded-lg p-2">
                    <item.icon className="text-[#005B96]" size={18} />
                  </div>
                  <span className="text-sm font-semibold text-gray-700">{item.text}</span>
                </div>
              ))}
            </div>

            <Link href="/about">
              <Button className="bg-[#005B96] hover:bg-[#003D66] text-white font-bold px-8 py-6 rounded-xl shadow-lg shadow-[#005B96]/20 hover:shadow-[#005B96]/30 transition-all btn-glow group">
                Learn More About Us
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={18} />
              </Button>
            </Link>
          </AnimatedSection>
        </div>
      </div>
    </section>
  );
}

// =================== WHY CHOOSE US ===================
function WhyChooseUs() {
  const reasons = [
    { icon: Zap, title: "Lightning Fast", description: "Optimized routes and priority carrier partnerships mean your shipments arrive faster. Same-day air freight and express ocean services available for urgent deadlines." },
    { icon: Shield, title: "Secure & Insured", description: "Every shipment is covered by comprehensive cargo insurance. State-of-the-art monitoring and tamper-proof seals ensure your goods stay protected throughout transit." },
    { icon: BarChart3, title: "Cost Effective", description: "Leverage our volume-based carrier negotiations and smart routing algorithms to get the most competitive rates without compromising on quality or speed." },
    { icon: HeadphonesIcon, title: "Dedicated Support", description: "A personal account manager and round-the-clock customer service team ensure you always have an expert to turn to. Multi-language support across all time zones." },
    { icon: Container, title: "End-to-End Solutions", description: "From pickup to final delivery, customs clearance to warehousing, we handle every step of the logistics chain so you can focus on growing your business." },
    { icon: Globe, title: "Global Reach", description: "With offices and partners in over 150 countries, we navigate local regulations, cultural nuances, and infrastructure challenges to deliver anywhere on the planet." },
  ];

  return (
    <section className="py-28 bg-white relative">
      <div className="absolute inset-0 diagonal-stripes" />
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-20">
          <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
            <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
            Why Sky Port
          </span>
          <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
            Why Businesses<br />
            <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Choose Us</span>
          </h2>
          <p className="mt-5 text-gray-500 max-w-2xl mx-auto text-lg leading-relaxed">
            We combine speed, security, and savings to deliver a logistics experience that sets your supply chain apart.
          </p>
        </AnimatedSection>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {reasons.map((reason, idx) => (
            <AnimatedSection key={reason.title} delay={idx * 0.08}>
              <div className="group relative bg-white rounded-2xl p-7 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 cursor-default border border-gray-100/80 card-border-glow h-full">
                <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-14 h-14 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-[#005B96]/20 transition-all duration-300">
                  <reason.icon className="text-white" size={24} />
                </div>
                <h3 className="text-lg font-bold text-[#003D66] mb-3">{reason.title}</h3>
                <p className="text-gray-500 leading-relaxed text-[15px]">{reason.description}</p>
              </div>
            </AnimatedSection>
          ))}
        </div>
      </div>
    </section>
  );
}

// =================== TRACKING CTA ===================
function TrackingSection() {
  const [trackingId, setTrackingId] = useState("");
  return (
    <section id="tracking" className="py-28 bg-gray-50 relative overflow-hidden">
      <div className="absolute inset-0 grid-pattern opacity-30" />
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection>
          <div className="relative overflow-hidden rounded-3xl">
            <div className="absolute inset-0 bg-gradient-to-br from-[#003D66] via-[#005B96] to-[#003D66]" />
            <div className="absolute inset-0 grid-pattern opacity-10" />
            <div className="absolute top-0 right-0 w-96 h-96 bg-[#FF7F00]/10 rounded-full blur-[100px]" />
            <div className="relative z-10 px-6 sm:px-16 py-20 text-center">
              <div className="w-16 h-16 rounded-2xl bg-[#FF7F00]/15 flex items-center justify-center mx-auto mb-8 border border-[#FF7F00]/20">
                <Package className="text-[#FF7F00]" size={32} />
              </div>
              <h2 className="text-4xl sm:text-5xl font-extrabold text-white mb-5 tracking-tight">
                Track Your Shipment
              </h2>
              <p className="text-white/60 max-w-lg mx-auto mb-10 text-lg leading-relaxed">
                Enter your tracking number to get real-time updates on your shipment&apos;s location, status, and estimated delivery time.
              </p>
              <div className="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
                <Input
                  placeholder="Enter tracking number (e.g., SKP-2026-XXXXX)"
                  value={trackingId}
                  onChange={(e) => setTrackingId(e.target.value)}
                  className="flex-1 h-14 bg-white/8 border-white/15 text-white placeholder:text-white/30 text-base rounded-xl px-6 backdrop-blur-sm focus:ring-[#FF7F00]/50"
                />
                <Link href="/tracking">
                  <Button className="h-14 bg-[#FF7F00] hover:bg-[#CC6600] text-white font-bold px-8 rounded-xl shadow-lg shadow-[#FF7F00]/25 text-base btn-glow group">
                    Track Now
                    <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={18} />
                  </Button>
                </Link>
              </div>
            </div>
          </div>
        </AnimatedSection>
      </div>
    </section>
  );
}

// =================== NETWORK SECTION ===================
function NetworkSection() {
  return (
    <section id="network" className="py-28 bg-white relative">
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-20">
          <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
            <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
            Our Reach
          </span>
          <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
            Global Network,<br />
            <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Local Expertise</span>
          </h2>
          <p className="mt-5 text-gray-500 max-w-2xl mx-auto text-lg leading-relaxed">
            With strategically placed hubs on every continent, we navigate local regulations and infrastructure to deliver seamless logistics anywhere in the world.
          </p>
        </AnimatedSection>

        <AnimatedSection>
          <div className="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#005B96]/10 group">
            <img src="/global-network.png" alt="Global logistics network" className="w-full h-auto group-hover:scale-105 transition-transform duration-700" />
            <div className="absolute inset-0 bg-gradient-to-t from-[#003D66]/90 via-[#003D66]/20 to-transparent" />
            <div className="absolute bottom-0 left-0 right-0 p-8 sm:p-12">
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-8">
                {[
                  { region: "Americas", offices: "12 offices" },
                  { region: "Europe", offices: "18 offices" },
                  { region: "Asia Pacific", offices: "24 offices" },
                  { region: "Middle East & Africa", offices: "10 offices" },
                ].map((item) => (
                  <div key={item.region} className="group/item">
                    <div className="text-[#FF7F00] font-bold text-lg mb-1 group-hover/item:text-[#FFA040] transition-colors">{item.region}</div>
                    <div className="text-white/60 text-sm">{item.offices}</div>
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

// =================== TESTIMONIALS ===================
function TestimonialsSection() {
  const testimonials = [
    { name: "Sarah Chen", role: "Supply Chain Director, TechVision Inc.", text: "Sky Port Logistics transformed our international supply chain. Their air freight services cut our delivery times by 40%, and the real-time tracking gives us complete visibility.", rating: 5 },
    { name: "Marcus Rodriguez", role: "CEO, FreshHarvest Exports", text: "We ship perishable goods across three continents, and Sky Port's temperature-controlled logistics have been flawless. Zero spoilage in two years of partnership.", rating: 5 },
    { name: "Amara Okafor", role: "Operations Manager, BuildRight Materials", text: "Moving heavy construction materials internationally is complex, but Sky Port makes it feel effortless. Their road transport and warehousing solutions are seamless.", rating: 5 },
  ];

  return (
    <section id="testimonials" className="py-28 bg-gray-50 relative overflow-hidden">
      <div className="absolute top-0 left-0 w-[400px] h-[400px] bg-[#005B96]/3 rounded-full blur-[120px]" />
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-20">
          <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
            <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
            Testimonials
          </span>
          <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
            What Our Clients<br />
            <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Say About Us</span>
          </h2>
        </AnimatedSection>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {testimonials.map((testimonial, idx) => (
            <AnimatedSection key={testimonial.name} delay={idx * 0.1}>
              <div className="bg-white rounded-2xl p-7 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 h-full flex flex-col">
                <div className="flex gap-1 mb-5">
                  {Array.from({ length: testimonial.rating }).map((_, i) => (
                    <Star key={i} className="text-[#FF7F00] fill-[#FF7F00]" size={16} />
                  ))}
                </div>
                <p className="text-gray-500 leading-relaxed mb-6 italic flex-1 text-[15px]">
                  &ldquo;{testimonial.text}&rdquo;
                </p>
                <div className="flex items-center gap-3 pt-5 border-t border-gray-100">
                  <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] text-white rounded-xl w-11 h-11 flex items-center justify-center font-bold text-sm shadow-md shadow-[#005B96]/20">
                    {testimonial.name.split(" ").map((n) => n[0]).join("")}
                  </div>
                  <div>
                    <div className="font-bold text-[#003D66] text-sm">{testimonial.name}</div>
                    <div className="text-gray-400 text-xs">{testimonial.role}</div>
                  </div>
                </div>
              </div>
            </AnimatedSection>
          ))}
        </div>
      </div>
    </section>
  );
}

// =================== CTA SECTION ===================
function CTASection() {
  return (
    <section className="py-24 relative overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-br from-[#FF7F00] via-[#CC6600] to-[#FF7F00]" />
      <div className="absolute inset-0 animate-gradient-shift" style={{ backgroundImage: "radial-gradient(circle at 30% 50%, rgba(255,160,64,0.4) 0%, transparent 50%), radial-gradient(circle at 70% 50%, rgba(204,102,0,0.4) 0%, transparent 50%)" }} />
      <div className="absolute inset-0 grid-pattern opacity-5" />
      <div className="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <AnimatedSection>
          <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-8 tracking-tight">
            Ready to Ship Smarter?
          </h2>
          <p className="text-white/80 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
            Join thousands of businesses that trust Sky Port Logistics for their global shipping needs. Get a free quote today and experience the difference.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link href="/contact">
              <Button size="lg" className="bg-white text-[#FF7F00] hover:bg-gray-50 font-bold px-8 py-7 text-lg rounded-xl shadow-xl group">
                Get Free Quote
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={20} />
              </Button>
            </Link>
            <a href="tel:+18005571234">
              <Button size="lg" variant="outline" className="border-white/25 text-white hover:bg-white/10 font-bold px-8 py-7 text-lg rounded-xl backdrop-blur-sm">
                <Phone className="mr-2" size={20} />
                Call Us Now
              </Button>
            </a>
          </div>
        </AnimatedSection>
      </div>
    </section>
  );
}

// =================== CONTACT SECTION ===================
function ContactSection() {
  const [formData, setFormData] = useState({ name: "", email: "", phone: "", service: "", message: "" });

  return (
    <section id="contact" className="py-28 bg-white relative">
      <div className="absolute inset-0 dot-pattern" />
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <AnimatedSection className="text-center mb-20">
          <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
            <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
            Get In Touch
          </span>
          <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
            Contact Us
          </h2>
          <p className="mt-5 text-gray-500 max-w-2xl mx-auto text-lg leading-relaxed">
            Have a shipment need or a question? Our team is ready to help you find the perfect logistics solution.
          </p>
        </AnimatedSection>

        <div className="grid grid-cols-1 lg:grid-cols-5 gap-12">
          <AnimatedSection className="lg:col-span-2">
            <div className="space-y-6">
              {[
                { icon: MapPin, title: "Head Office", lines: ["Sky Port Tower, 1200 Harbor Blvd", "Suite 800, Los Angeles, CA 90012"] },
                { icon: Phone, title: "Phone", lines: ["+1 (800) 557-1234 (Toll Free)", "+1 (213) 555-6789"] },
                { icon: Mail, title: "Email", lines: ["info@skyportlogistics.com", "quotes@skyportlogistics.com"] },
                { icon: Clock, title: "Working Hours", lines: ["Mon – Fri: 8:00 AM – 8:00 PM", "Sat – Sun: Emergency Line Only"] },
              ].map((item) => (
                <div key={item.title} className="flex gap-4 bg-gray-50 rounded-xl p-4 border border-gray-100/80">
                  <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl p-3 h-fit shadow-md shadow-[#005B96]/10">
                    <item.icon className="text-white" size={20} />
                  </div>
                  <div>
                    <div className="font-bold text-[#003D66] text-sm mb-1">{item.title}</div>
                    {item.lines.map((line) => (
                      <div key={line} className="text-gray-500 text-sm">{line}</div>
                    ))}
                  </div>
                </div>
              ))}
            </div>
          </AnimatedSection>

          <AnimatedSection className="lg:col-span-3" delay={0.2}>
            <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-xl shadow-[#005B96]/5 border border-gray-100/80">
              <h3 className="text-xl font-bold text-[#003D66] mb-6">Request a Free Quote</h3>
              <form onSubmit={(e) => { e.preventDefault(); alert("Thank you! We will contact you within 2 business hours."); }} className="space-y-5">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div>
                    <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">Full Name *</label>
                    <Input placeholder="John Doe" value={formData.name} onChange={(e) => setFormData({ ...formData, name: e.target.value })} className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30" required />
                  </div>
                  <div>
                    <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">Email *</label>
                    <Input type="email" placeholder="john@company.com" value={formData.email} onChange={(e) => setFormData({ ...formData, email: e.target.value })} className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30" required />
                  </div>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div>
                    <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">Phone</label>
                    <Input placeholder="+1 (555) 000-0000" value={formData.phone} onChange={(e) => setFormData({ ...formData, phone: e.target.value })} className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30" />
                  </div>
                  <div>
                    <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">Service Needed</label>
                    <Input placeholder="Air Freight, Ocean, etc." value={formData.service} onChange={(e) => setFormData({ ...formData, service: e.target.value })} className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30" />
                  </div>
                </div>
                <div>
                  <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">Message *</label>
                  <Textarea placeholder="Tell us about your shipping needs..." rows={4} value={formData.message} onChange={(e) => setFormData({ ...formData, message: e.target.value })} className="rounded-xl border-gray-200 focus:ring-[#FF7F00]/30" required />
                </div>
                <Button type="submit" className="w-full bg-[#FF7F00] hover:bg-[#CC6600] text-white font-bold h-12 rounded-xl text-base btn-glow group">
                  Send Message
                  <Send className="ml-2 group-hover:translate-x-1 transition-transform" size={18} />
                </Button>
              </form>
            </div>
          </AnimatedSection>
        </div>
      </div>
    </section>
  );
}

// =================== MAIN PAGE ===================
export default function HomePage() {
  return (
    <>
      <HeroSection />
      <PartnersMarquee />
      <ServicesSection />
      <StatsSection />
      <AboutSection />
      <WhyChooseUs />
      <TrackingSection />
      <NetworkSection />
      <TestimonialsSection />
      <CTASection />
      <ContactSection />
    </>
  );
}
