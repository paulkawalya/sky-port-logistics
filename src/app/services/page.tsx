"use client";

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
  HeadphonesIcon,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import AnimatedSection from "@/components/AnimatedSection";

const services = [
  {
    icon: Plane,
    title: "Air Freight",
    slug: "air-freight",
    description:
      "Express air cargo services with guaranteed delivery timelines. Priority boarding, charter services, and temperature-controlled transport for time-sensitive and high-value shipments across all continents.",
    features: ["Same-day delivery options", "Temperature-controlled cargo", "Charter flight services", "Door-to-door service"],
    image: "/cargo-plane.png",
    color: "from-sky-500 to-blue-600",
  },
  {
    icon: Ship,
    title: "Ocean Freight",
    slug: "ocean-freight",
    description:
      "Comprehensive ocean shipping solutions for FCL and LCL. Global partnerships with major carriers ensure competitive rates, flexible scheduling, and reliable transit times for every trade lane.",
    features: ["FCL & LCL options", "Competitive global rates", "Refrigerated containers", "Port-to-port & door-to-door"],
    image: "/cargo-ship.png",
    color: "from-blue-600 to-indigo-700",
  },
  {
    icon: Truck,
    title: "Road Transport",
    slug: "road-transport",
    description:
      "Efficient road freight and last-mile delivery connecting ports, warehouses, and final destinations. Refrigerated trucks, flatbeds, and specialized vehicles with real-time GPS tracking.",
    features: ["GPS-tracked fleet", "Refrigerated transport", "Cross-border solutions", "Last-mile delivery"],
    image: "/warehouse.png",
    color: "from-[#005B96] to-[#003D66]",
  },
  {
    icon: Warehouse,
    title: "Warehousing",
    slug: "warehousing",
    description:
      "State-of-the-art warehousing and distribution centers near major ports and airports. Inventory management, order fulfillment, pick-and-pack, and bonded warehouse facilities with 24/7 security.",
    features: ["Inventory management", "Bonded facilities", "24/7 security", "Pick & pack services"],
    image: "/warehouse.png",
    color: "from-[#FF7F00] to-[#CC6600]",
  },
];

export default function ServicesPage() {
  return (
    <div className="pt-20">
      {/* Hero */}
      <section className="relative py-28 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-[#003D66] via-[#005B96] to-[#003D66]" />
        <div className="absolute inset-0 grid-pattern opacity-20" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <AnimatedSection>
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2 mb-8">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Our Services
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">
              Comprehensive Logistics{" "}
              <span className="bg-gradient-to-r from-[#FF7F00] to-[#FFA040] bg-clip-text text-transparent">Solutions</span>
            </h1>
            <p className="text-white/70 text-lg max-w-2xl mx-auto leading-relaxed">
              From air to ocean, road to warehouse — we provide end-to-end
              supply chain solutions tailored to your business needs, delivered
              with speed and reliability.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Services Grid */}
      <section className="py-28 bg-gray-50 relative">
        <div className="absolute inset-0 dot-pattern" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            {services.map((service, idx) => (
              <AnimatedSection key={service.slug} delay={idx * 0.15}>
                <Link href={`/services/${service.slug}`}>
                  <div className="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-[#005B96]/8 transition-all duration-500 cursor-pointer border border-gray-100/80 card-border-glow h-full">
                    <div className="relative h-52 overflow-hidden">
                      <img
                        src={service.image}
                        alt={service.title}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-[#003D66]/80 via-[#003D66]/30 to-transparent" />
                      <div className="absolute top-4 left-4">
                        <div className="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/20">
                          <service.icon className="text-white" size={24} />
                        </div>
                      </div>
                      <div className="absolute bottom-4 left-4 right-4">
                        <h3 className="text-2xl font-extrabold text-white tracking-tight">{service.title}</h3>
                      </div>
                    </div>
                    <div className="p-6">
                      <p className="text-gray-500 leading-relaxed mb-5 text-[15px]">
                        {service.description}
                      </p>
                      <ul className="space-y-2.5 mb-5">
                        {service.features.map((feature) => (
                          <li
                            key={feature}
                            className="flex items-center gap-2.5 text-sm text-gray-600"
                          >
                            <CheckCircle className="text-[#FF7F00] flex-shrink-0" size={16} />
                            {feature}
                          </li>
                        ))}
                      </ul>
                      <span className="inline-flex items-center gap-2 text-[#FF7F00] font-semibold text-sm group-hover:gap-3 transition-all">
                        Learn More <ArrowRight size={16} />
                      </span>
                    </div>
                  </div>
                </Link>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* Why Choose Our Services */}
      <section className="py-28 bg-white relative">
        <div className="absolute inset-0 diagonal-stripes" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Why Sky Port
            </span>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Built for{" "}
              <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Performance</span>
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
            {[
              {
                icon: Shield,
                title: "Fully Insured",
                description: "Every shipment is covered by comprehensive cargo insurance for complete peace of mind.",
              },
              {
                icon: Clock,
                title: "Real-Time Tracking",
                description: "Monitor your shipments 24/7 with our proprietary tracking technology and instant notifications.",
              },
              {
                icon: HeadphonesIcon,
                title: "Dedicated Support",
                description: "Personal account managers and round-the-clock customer service in multiple languages.",
              },
            ].map((item, idx) => (
              <AnimatedSection key={item.title} delay={idx * 0.1}>
                <div className="group relative bg-white rounded-2xl p-7 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 card-border-glow h-full text-center">
                  <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-16 h-16 flex items-center justify-center mx-auto mb-6 shadow-md shadow-[#005B96]/20 group-hover:scale-110 transition-all duration-300">
                    <item.icon className="text-white" size={28} />
                  </div>
                  <h3 className="text-lg font-bold text-[#003D66] mb-3">
                    {item.title}
                  </h3>
                  <p className="text-gray-500 leading-relaxed text-[15px]">
                    {item.description}
                  </p>
                </div>
              </AnimatedSection>
            ))}
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
              Need a Custom Solution?
            </h2>
            <p className="text-white/80 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
              Our logistics experts will design a tailored solution for your
              unique shipping requirements. Get a free consultation today.
            </p>
            <Link href="/contact">
              <Button
                size="lg"
                className="bg-white text-[#FF7F00] hover:bg-gray-50 font-bold px-8 py-7 text-lg rounded-xl shadow-xl group"
              >
                Get Free Quote
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={20} />
              </Button>
            </Link>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
