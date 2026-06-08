"use client";

import { useParams } from "next/navigation";
import Link from "next/link";
import {
  Plane,
  Ship,
  Truck,
  Warehouse,
  ArrowRight,
  CheckCircle,
  Shield,
  Clock,
  Globe,
  Zap,
  BarChart3,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import AnimatedSection from "@/components/AnimatedSection";

const serviceData: Record<string, {
  icon: React.ComponentType<{ className?: string; size?: number }>;
  title: string;
  image: string;
  color: string;
  heroGradient: string;
  subtitle: string;
  description: string;
  longDescription: string;
  features: { icon: React.ComponentType<{ className?: string; size?: number }>; title: string; description: string }[];
  benefits: string[];
  details: { title: string; content: string }[];
}> = {
  "air-freight": {
    icon: Plane,
    title: "Air Freight",
    image: "/cargo-plane.png",
    color: "from-sky-500 to-blue-600",
    heroGradient: "from-[#003D66]/90 via-[#005B96]/80 to-[#0078C8]/70",
    subtitle: "Express Air Cargo Solutions",
    description: "Priority air freight with guaranteed delivery timelines across all continents.",
    longDescription: "Sky Port Logistics offers comprehensive air freight services designed for time-sensitive and high-value shipments. With partnerships spanning over 50 leading airlines, we provide priority boarding, charter services, and temperature-controlled transport that connects your business to every major hub on the planet. Our dedicated air cargo team monitors every shipment in real-time, proactively managing exceptions and ensuring your goods arrive on schedule — every time.",
    features: [
      { icon: Zap, title: "Same-Day Delivery", description: "Express same-day and next-flight-out options for urgent shipments that can't wait." },
      { icon: Shield, title: "Temperature-Controlled", description: "Cold chain air cargo for pharmaceuticals, perishables, and temperature-sensitive goods." },
      { icon: Globe, title: "Global Network", description: "Connected to 300+ airports worldwide with priority access to major cargo hubs." },
      { icon: BarChart3, title: "Charter Services", description: "Dedicated charter flights for oversized, high-security, or time-critical cargo." },
    ],
    benefits: [
      "Guaranteed delivery timelines with SLA-backed commitments",
      "Real-time shipment tracking with proactive exception management",
      "Temperature-controlled cargo handling (2-8°C, 15-25°C, and deep freeze)",
      "Door-to-door and airport-to-airport service options",
      "Customs brokerage and documentation handling",
      "Dangerous goods (DG) certified handling and packaging",
      "Consolidation and deconsolidation services",
      "24/7 dedicated air cargo support desk",
    ],
    details: [
      { title: "Express & Priority Services", content: "Our express air freight service is designed for shipments that need to move fast. With same-day pickup and next-flight-out options, we ensure your urgent cargo reaches its destination on time. Priority boarding on commercial flights means your goods are first on and first off." },
      { title: "Charter & Project Cargo", content: "For oversized, high-value, or time-critical shipments, our charter service provides a dedicated aircraft tailored to your specifications. From heavy machinery to humanitarian aid, we manage every aspect of the charter logistics, including permits, ground handling, and customs clearance." },
    ],
  },
  "ocean-freight": {
    icon: Ship,
    title: "Ocean Freight",
    image: "/cargo-ship.png",
    color: "from-blue-600 to-indigo-700",
    heroGradient: "from-[#003D66]/90 via-[#005B96]/80 to-[#003D66]/70",
    subtitle: "Global Ocean Shipping Solutions",
    description: "Comprehensive FCL and LCL ocean freight with competitive rates and reliable transit times.",
    longDescription: "Sky Port Logistics provides comprehensive ocean freight solutions for businesses of every size. Whether you need Full Container Load (FCL) or Less-than-Container Load (LCL) services, our global partnerships with major carriers ensure competitive rates, flexible scheduling, and reliable transit times across every trade lane. From refrigerated containers for perishables to specialized equipment for oversized cargo, we handle it all with precision and care.",
    features: [
      { icon: Ship, title: "FCL & LCL", description: "Full container and consolidated shipping options for any volume requirement." },
      { icon: Shield, title: "Reefer Cargo", description: "Temperature-controlled containers for perishables, pharmaceuticals, and chemicals." },
      { icon: Globe, title: "Global Coverage", description: "Service across all major trade lanes with connections to 500+ ports worldwide." },
      { icon: BarChart3, title: "Competitive Rates", description: "Volume-based carrier negotiations to deliver the most cost-effective ocean solutions." },
    ],
    benefits: [
      "FCL (20ft, 40ft, 40HC, 45HC) and LCL consolidation services",
      "Competitive rates through volume-based carrier partnerships",
      "Refrigerated container (reefer) management and monitoring",
      "Breakbulk and project cargo handling",
      "Door-to-door and port-to-port service options",
      "Comprehensive marine cargo insurance",
      "Real-time container tracking and ETAs",
      "Customs brokerage and trade compliance services",
    ],
    details: [
      { title: "FCL Services", content: "Our Full Container Load service provides dedicated containers for your exclusive use, ensuring your cargo is handled with maximum care and minimal handling. We offer competitive rates on all container types and manage the entire process from booking through final delivery." },
      { title: "LCL Consolidation", content: "For smaller shipments that don't fill a full container, our LCL consolidation service combines your cargo with other shipments to provide cost-effective ocean transport. We manage the entire consolidation process at our strategically located CFS facilities." },
    ],
  },
  "road-transport": {
    icon: Truck,
    title: "Road Transport",
    image: "/warehouse.png",
    color: "from-[#005B96] to-[#003D66]",
    heroGradient: "from-[#005B96]/90 via-[#003D66]/80 to-[#005B96]/70",
    subtitle: "Efficient Road Freight Solutions",
    description: "GPS-tracked road freight and last-mile delivery connecting every point in the supply chain.",
    longDescription: "Sky Port Logistics offers efficient road freight and last-mile delivery solutions that bridge the gap between ports, warehouses, and final destinations. Our diverse fleet includes refrigerated trucks, flatbeds, tankers, and specialized vehicles capable of handling any cargo type. With real-time GPS tracking, experienced drivers, and comprehensive route optimization, we ensure your goods move efficiently and safely across borders and through cities.",
    features: [
      { icon: Truck, title: "GPS-Tracked Fleet", description: "Real-time tracking on every vehicle with route optimization and ETA updates." },
      { icon: Shield, title: "Refrigerated Transport", description: "Temperature-controlled trucks for perishables, pharma, and cold chain logistics." },
      { icon: Globe, title: "Cross-Border", description: "Seamless cross-border operations with customs pre-clearance and documentation." },
      { icon: Zap, title: "Last-Mile Delivery", description: "Efficient last-mile solutions for urban and rural delivery across all regions." },
    ],
    benefits: [
      "GPS-tracked fleet with real-time visibility and ETA predictions",
      "Refrigerated and temperature-controlled transport options",
      "Cross-border solutions with customs pre-clearance",
      "Full truckload (FTL) and less-than-truckload (LTL) options",
      "Specialized equipment for oversized and hazardous cargo",
      "Dedicated and shared fleet options for flexibility",
      "Route optimization for cost and time efficiency",
      "24/7 dispatch and emergency response capabilities",
    ],
    details: [
      { title: "Full Truckload (FTL)", content: "When you need a dedicated truck for your shipment, our FTL service provides exclusive use of the vehicle from origin to destination. This ensures faster transit times, minimal handling, and maximum security for your cargo." },
      { title: "Last-Mile Solutions", content: "Our last-mile delivery service ensures your goods reach their final destination efficiently, whether it's a warehouse, retail store, or residential address. We offer same-day and scheduled delivery options with real-time proof of delivery." },
    ],
  },
  warehousing: {
    icon: Warehouse,
    title: "Warehousing",
    image: "/warehouse.png",
    color: "from-[#FF7F00] to-[#CC6600]",
    heroGradient: "from-[#CC6600]/90 via-[#FF7F00]/80 to-[#CC6600]/70",
    subtitle: "Smart Warehousing & Distribution",
    description: "State-of-the-art storage, inventory management, and distribution near major ports and airports.",
    longDescription: "Sky Port Logistics operates state-of-the-art warehousing and distribution centers strategically located near major ports, airports, and transportation hubs worldwide. Our facilities offer comprehensive inventory management, order fulfillment, pick-and-pack services, and bonded warehouse capabilities. With 24/7 security monitoring, climate-controlled storage, and advanced warehouse management systems (WMS), we provide the infrastructure and expertise to optimize your supply chain.",
    features: [
      { icon: Warehouse, title: "Strategic Locations", description: "Warehouses near major ports and airports for rapid distribution and minimal transit times." },
      { icon: Shield, title: "24/7 Security", description: "Round-the-clock security with CCTV, access control, and fire suppression systems." },
      { icon: BarChart3, title: "Inventory Management", description: "Advanced WMS with real-time stock visibility, automated replenishment, and reporting." },
      { icon: Zap, title: "Order Fulfillment", description: "Pick-and-pack services with same-day processing and multi-channel fulfillment capabilities." },
    ],
    benefits: [
      "Strategic warehouse locations near major ports and airports",
      "Bonded warehouse facilities for duty-deferred storage",
      "Climate-controlled and ambient storage options",
      "Advanced warehouse management system (WMS)",
      "Pick-and-pack and order fulfillment services",
      "Cross-docking and transloading capabilities",
      "Inventory reporting and analytics dashboard",
      "24/7 security with CCTV and access control",
    ],
    details: [
      { title: "Bonded Warehousing", content: "Our bonded warehouse facilities allow you to store imported goods without paying duties until the goods are released for domestic consumption. This provides significant cash flow advantages and flexibility in managing your inventory." },
      { title: "E-Commerce Fulfillment", content: "Our e-commerce fulfillment centers are designed for speed and accuracy. With same-day order processing, multi-channel fulfillment, and seamless integration with major e-commerce platforms, we help you deliver exceptional customer experiences." },
    ],
  },
};

const allServices = [
  { slug: "air-freight", icon: Plane, title: "Air Freight" },
  { slug: "ocean-freight", icon: Ship, title: "Ocean Freight" },
  { slug: "road-transport", icon: Truck, title: "Road Transport" },
  { slug: "warehousing", icon: Warehouse, title: "Warehousing" },
];

export default function ServicePage() {
  const params = useParams();
  const slug = params.slug as string;
  const service = serviceData[slug];

  if (!service) {
    return (
      <div className="pt-20 min-h-screen flex items-center justify-center">
        <div className="text-center">
          <h1 className="text-4xl font-extrabold text-[#003D66] mb-4 tracking-tight">Service Not Found</h1>
          <p className="text-gray-600 mb-6">The service you are looking for does not exist.</p>
          <Link href="/services">
            <Button className="bg-[#FF7F00] hover:bg-[#CC6600] text-white rounded-xl px-6 btn-glow shadow-lg shadow-[#FF7F00]/25">
              Back to Services
            </Button>
          </Link>
        </div>
      </div>
    );
  }

  const Icon = service.icon;
  const relatedServices = allServices.filter((s) => s.slug !== slug);

  return (
    <div className="pt-20">
      {/* Hero */}
      <section className="relative py-28 overflow-hidden">
        <div className="absolute inset-0">
          <img
            src={service.image}
            alt={service.title}
            className="w-full h-full object-cover"
          />
          <div className={`absolute inset-0 bg-gradient-to-r ${service.heroGradient}`} />
        </div>
        <div className="absolute inset-0 grid-pattern opacity-10" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection>
            <div className="max-w-3xl">
              <div className="flex items-center gap-3 mb-8">
                <div className="bg-white/15 backdrop-blur-md rounded-xl p-3 border border-white/20">
                  <Icon className="text-white" size={32} />
                </div>
                <span className="text-white/80 text-lg">{service.subtitle}</span>
              </div>
              <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">
                {service.title}
              </h1>
              <p className="text-white/70 text-lg mb-10 max-w-2xl leading-relaxed">
                {service.description}
              </p>
              <Link href="/contact">
                <Button
                  size="lg"
                  className="bg-[#FF7F00] hover:bg-[#CC6600] text-white font-bold px-8 py-7 text-lg rounded-xl shadow-xl shadow-[#FF7F00]/25 btn-glow group"
                >
                  Get a Quote
                  <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={18} />
                </Button>
              </Link>
            </div>
          </AnimatedSection>
        </div>
      </section>

      {/* Key Features */}
      <section className="py-28 bg-white relative">
        <div className="absolute inset-0 dot-pattern" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Key Features
            </span>
            <h2 className="text-4xl sm:text-5xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              What Sets Us Apart
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {service.features.map((feature, idx) => {
              const FeatureIcon = feature.icon;
              return (
                <AnimatedSection key={feature.title} delay={idx * 0.1}>
                  <div className="group relative bg-white rounded-2xl p-7 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 card-border-glow h-full text-center">
                    <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-14 h-14 flex items-center justify-center mx-auto mb-5 shadow-md shadow-[#005B96]/20 group-hover:scale-110 transition-all duration-300">
                      <FeatureIcon className="text-white" size={24} />
                    </div>
                    <h3 className="text-lg font-bold text-[#003D66] mb-2">
                      {feature.title}
                    </h3>
                    <p className="text-gray-500 text-sm leading-relaxed">
                      {feature.description}
                    </p>
                  </div>
                </AnimatedSection>
              );
            })}
          </div>
        </div>
      </section>

      {/* Service Details */}
      <section className="py-28 bg-gray-50 relative">
        <div className="absolute inset-0 grid-pattern opacity-30" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              In Detail
            </span>
            <h2 className="text-4xl sm:text-5xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Service Details
            </h2>
          </AnimatedSection>

          <div className="space-y-16">
            {service.details.map((detail, idx) => (
              <AnimatedSection key={detail.title} delay={idx * 0.15}>
                <div className={`grid grid-cols-1 lg:grid-cols-2 gap-12 items-center ${idx % 2 === 1 ? "lg:flex-row-reverse" : ""}`}>
                  <div className={idx % 2 === 1 ? "lg:order-2" : ""}>
                    <div className="relative rounded-2xl overflow-hidden shadow-xl">
                      <img
                        src={service.image}
                        alt={detail.title}
                        className="w-full h-72 object-cover"
                      />
                      <div className={`absolute inset-0 bg-gradient-to-t ${service.color} opacity-30`} />
                    </div>
                  </div>
                  <div className={idx % 2 === 1 ? "lg:order-1" : ""}>
                    <h3 className="text-2xl font-extrabold text-[#003D66] mb-4 tracking-tight">
                      {detail.title}
                    </h3>
                    <p className="text-gray-500 leading-relaxed text-[15px]">
                      {detail.content}
                    </p>
                  </div>
                </div>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* Benefits */}
      <section className="py-28 bg-white relative">
        <div className="absolute inset-0 diagonal-stripes" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <AnimatedSection>
              <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
                <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
                Benefits
              </span>
              <h2 className="text-4xl sm:text-5xl font-extrabold text-[#003D66] mt-5 mb-8 tracking-tight">
                Why Choose Our {service.title}
              </h2>
              <ul className="space-y-4">
                {service.benefits.map((benefit) => (
                  <li key={benefit} className="flex items-start gap-3">
                    <CheckCircle className="text-[#FF7F00] flex-shrink-0 mt-0.5" size={20} />
                    <span className="text-gray-600">{benefit}</span>
                  </li>
                ))}
              </ul>
            </AnimatedSection>

            <AnimatedSection delay={0.2}>
              <div className="relative rounded-2xl overflow-hidden shadow-2xl">
                <img
                  src={service.image}
                  alt={service.title}
                  className="w-full h-auto"
                />
                <div className={`absolute inset-0 bg-gradient-to-t ${service.color} opacity-40`} />
              </div>
            </AnimatedSection>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-24 relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-[#FF7F00] via-[#CC6600] to-[#FF7F00]" />
        <div className="absolute inset-0 animate-gradient-shift" style={{ backgroundImage: "radial-gradient(circle at 30% 50%, rgba(255,160,64,0.4) 0%, transparent 50%), radial-gradient(circle at 70% 50%, rgba(204,102,0,0.4) 0%, transparent 50%)" }} />
        <div className="absolute inset-0 grid-pattern opacity-5" />
        <div className="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <AnimatedSection>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-8 tracking-tight">
              Ready to Ship with {service.title}?
            </h2>
            <p className="text-white/80 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
              Get a customized quote for your {service.title.toLowerCase()} needs. Our experts
              are ready to design the perfect solution for your business.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link href="/contact">
                <Button
                  size="lg"
                  className="bg-white text-[#FF7F00] hover:bg-gray-50 font-bold px-8 py-7 text-lg rounded-xl shadow-xl group"
                >
                  Get Free Quote
                  <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={20} />
                </Button>
              </Link>
              <Link href="/services">
                <Button
                  size="lg"
                  variant="outline"
                  className="border-white/25 text-white hover:bg-white/10 font-bold px-8 py-7 text-lg rounded-xl backdrop-blur-sm"
                >
                  View All Services
                </Button>
              </Link>
            </div>
          </AnimatedSection>
        </div>
      </section>

      {/* Related Services */}
      <section className="py-28 bg-gray-50 relative">
        <div className="absolute inset-0 dot-pattern" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-12">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Explore More
            </span>
            <h2 className="text-4xl sm:text-5xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Related Services
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
            {relatedServices.map((related, idx) => (
              <AnimatedSection key={related.slug} delay={idx * 0.1}>
                <Link href={`/services/${related.slug}`}>
                  <div className="group bg-white rounded-2xl p-7 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 cursor-pointer border border-gray-100/80 card-border-glow text-center">
                    <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-14 h-14 flex items-center justify-center mx-auto mb-4 shadow-md shadow-[#005B96]/20 group-hover:scale-110 transition-all duration-300">
                      <related.icon className="text-white" size={24} />
                    </div>
                    <h3 className="text-lg font-bold text-[#003D66] mb-2">
                      {related.title}
                    </h3>
                    <span className="inline-flex items-center gap-1 text-[#FF7F00] text-sm font-semibold group-hover:gap-2 transition-all">
                      Learn More <ArrowRight size={14} />
                    </span>
                  </div>
                </Link>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
