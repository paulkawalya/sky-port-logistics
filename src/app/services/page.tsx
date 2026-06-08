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
      <section className="relative py-24 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-r from-[#005B96] to-[#003D66]" />
        <div
          className="absolute inset-0 opacity-10"
          style={{
            backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
          }}
        />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <AnimatedSection>
            <span className="inline-flex items-center gap-2 bg-[#FF7F00]/20 text-[#FFA040] border border-[#FF7F00]/30 rounded-full px-4 py-2 text-sm font-medium mb-6">
              Our Services
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
              Comprehensive Logistics{" "}
              <span className="text-[#FF7F00]">Solutions</span>
            </h1>
            <p className="text-white/80 text-lg max-w-2xl mx-auto">
              From air to ocean, road to warehouse — we provide end-to-end
              supply chain solutions tailored to your business needs, delivered
              with speed and reliability.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Services Grid */}
      <section className="py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {services.map((service, idx) => (
              <AnimatedSection key={service.slug} delay={idx * 0.15}>
                <Link href={`/services/${service.slug}`}>
                  <Card className="group overflow-hidden border-0 shadow-lg hover:shadow-2xl transition-all duration-500 h-full cursor-pointer">
                    <div className="relative h-56 overflow-hidden">
                      <img
                        src={service.image}
                        alt={service.title}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                      />
                      <div
                        className={`absolute inset-0 bg-gradient-to-t ${service.color} opacity-70`}
                      />
                      <div className="absolute inset-0 flex items-center justify-center">
                        <div className="bg-white/20 backdrop-blur-sm rounded-full p-5">
                          <service.icon className="text-white" size={48} />
                        </div>
                      </div>
                    </div>
                    <CardContent className="p-6">
                      <h3 className="text-2xl font-bold text-[#005B96] mb-3">
                        {service.title}
                      </h3>
                      <p className="text-gray-600 leading-relaxed mb-4">
                        {service.description}
                      </p>
                      <ul className="space-y-2 mb-6">
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
                      <span className="inline-flex items-center gap-2 text-[#FF7F00] font-semibold text-sm group-hover:gap-3 transition-all">
                        Learn More <ArrowRight size={16} />
                      </span>
                    </CardContent>
                  </Card>
                </Link>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* Why Choose Our Services */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-16">
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              Why Sky Port
            </span>
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
              Built for Performance
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-8">
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
                <Card className="border-0 shadow-md hover:shadow-xl transition-all duration-300 h-full text-center">
                  <CardContent className="p-8">
                    <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-16 h-16 flex items-center justify-center mx-auto mb-5">
                      <item.icon className="text-white" size={28} />
                    </div>
                    <h3 className="text-lg font-bold text-[#005B96] mb-3">
                      {item.title}
                    </h3>
                    <p className="text-gray-600 leading-relaxed">
                      {item.description}
                    </p>
                  </CardContent>
                </Card>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
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
              Need a Custom Solution?
            </h2>
            <p className="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
              Our logistics experts will design a tailored solution for your
              unique shipping requirements. Get a free consultation today.
            </p>
            <Link href="/contact">
              <Button
                size="lg"
                className="bg-white text-[#FF7F00] hover:bg-gray-100 font-bold px-8 py-6 text-lg rounded-full shadow-xl group"
              >
                Get Free Quote
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
              </Button>
            </Link>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
