"use client";

import Link from "next/link";
import {
  Factory,
  Pill,
  Cpu,
  ShoppingBag,
  Zap,
  UtensilsCrossed,
  ArrowRight,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import AnimatedSection from "@/components/AnimatedSection";

const industries = [
  {
    icon: Factory,
    title: "Automotive",
    slug: "automotive",
    description:
      "Vehicle and parts logistics with just-in-time delivery, VMI programs, and specialized handling for oversized components across global manufacturing hubs.",
    color: "from-slate-600 to-slate-800",
  },
  {
    icon: Pill,
    title: "Pharmaceutical",
    slug: "pharmaceutical",
    description:
      "GDP-compliant temperature-controlled transport for pharmaceuticals, biologics, and clinical trial materials with unbroken cold chain integrity.",
    color: "from-emerald-500 to-emerald-700",
  },
  {
    icon: Cpu,
    title: "Technology",
    slug: "technology",
    description:
      "Secure, high-value technology shipments with anti-static handling, chain-of-custody tracking, and white-glove delivery for sensitive electronics.",
    color: "from-violet-500 to-violet-700",
  },
  {
    icon: ShoppingBag,
    title: "Retail & E-Commerce",
    slug: "retail",
    description:
      "Fast fulfillment and last-mile delivery solutions for retail and e-commerce with multi-channel distribution and returns management.",
    color: "from-rose-500 to-rose-700",
  },
  {
    icon: Zap,
    title: "Energy & Mining",
    slug: "energy",
    description:
      "Heavy equipment and hazardous materials logistics with specialized permits, route surveys, and HAZMAT-certified handling for the energy sector.",
    color: "from-amber-500 to-amber-700",
  },
  {
    icon: UtensilsCrossed,
    title: "Food & Beverage",
    slug: "food-beverage",
    description:
      "Cold chain and perishable goods logistics with temperature monitoring, HACCP compliance, and time-definite delivery for fresh produce and beverages.",
    color: "from-[#005B96] to-[#003D66]",
  },
];

export default function IndustriesPage() {
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
              Industry Solutions
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
              Tailored Solutions for{" "}
              <span className="text-[#FF7F00]">Every Industry</span>
            </h1>
            <p className="text-white/80 text-lg max-w-2xl mx-auto">
              We understand that every industry has unique logistics challenges.
              Our specialized teams design solutions that address the specific
              needs of your sector.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Industries Grid */}
      <section className="py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {industries.map((industry, idx) => (
              <AnimatedSection key={industry.slug} delay={idx * 0.1}>
                <Link href={`/industries/${industry.slug}`}>
                  <Card className="group border-0 shadow-lg hover:shadow-2xl transition-all duration-500 h-full cursor-pointer overflow-hidden">
                    <div className={`relative h-40 bg-gradient-to-br ${industry.color} flex items-center justify-center`}>
                      <industry.icon className="text-white/30 absolute" size={120} />
                      <div className="relative bg-white/20 backdrop-blur-sm rounded-full p-5 group-hover:scale-110 transition-transform">
                        <industry.icon className="text-white" size={40} />
                      </div>
                    </div>
                    <CardContent className="p-6">
                      <h3 className="text-xl font-bold text-[#005B96] mb-3">
                        {industry.title}
                      </h3>
                      <p className="text-gray-600 leading-relaxed mb-4 text-sm">
                        {industry.description}
                      </p>
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
              Don&apos;t See Your Industry?
            </h2>
            <p className="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
              We serve businesses across every sector. Contact us to discuss
              your specific logistics challenges and discover how we can help.
            </p>
            <Link href="/contact">
              <Button
                size="lg"
                className="bg-white text-[#FF7F00] hover:bg-gray-100 font-bold px-8 py-6 text-lg rounded-full shadow-xl group"
              >
                Contact Our Team
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
              </Button>
            </Link>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
