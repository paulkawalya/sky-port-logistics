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
      <section className="relative py-28 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-[#003D66] via-[#005B96] to-[#003D66]" />
        <div className="absolute inset-0 grid-pattern opacity-20" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <AnimatedSection>
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2 mb-8">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Industry Solutions
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">
              Tailored Solutions for{" "}
              <span className="bg-gradient-to-r from-[#FF7F00] to-[#FFA040] bg-clip-text text-transparent">Every Industry</span>
            </h1>
            <p className="text-white/70 text-lg max-w-2xl mx-auto leading-relaxed">
              We understand that every industry has unique logistics challenges.
              Our specialized teams design solutions that address the specific
              needs of your sector.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Industries Grid */}
      <section className="py-28 bg-gray-50 relative">
        <div className="absolute inset-0 dot-pattern" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {industries.map((industry, idx) => (
              <AnimatedSection key={industry.slug} delay={idx * 0.1}>
                <Link href={`/industries/${industry.slug}`}>
                  <div className="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-[#005B96]/8 transition-all duration-500 h-full cursor-pointer border border-gray-100/80 card-border-glow">
                    <div className={`relative h-40 bg-gradient-to-br ${industry.color} flex items-center justify-center`}>
                      <industry.icon className="text-white/30 absolute" size={120} />
                      <div className="relative bg-white/15 backdrop-blur-md rounded-xl p-5 border border-white/20 group-hover:scale-110 transition-transform duration-300">
                        <industry.icon className="text-white" size={40} />
                      </div>
                    </div>
                    <div className="p-6">
                      <h3 className="text-xl font-bold text-[#003D66] mb-3">
                        {industry.title}
                      </h3>
                      <p className="text-gray-500 leading-relaxed mb-4 text-sm">
                        {industry.description}
                      </p>
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

      {/* CTA */}
      <section className="py-24 relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-[#FF7F00] via-[#CC6600] to-[#FF7F00]" />
        <div className="absolute inset-0 animate-gradient-shift" style={{ backgroundImage: "radial-gradient(circle at 30% 50%, rgba(255,160,64,0.4) 0%, transparent 50%), radial-gradient(circle at 70% 50%, rgba(204,102,0,0.4) 0%, transparent 50%)" }} />
        <div className="absolute inset-0 grid-pattern opacity-5" />
        <div className="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <AnimatedSection>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-8 tracking-tight">
              Don&apos;t See Your Industry?
            </h2>
            <p className="text-white/80 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
              We serve businesses across every sector. Contact us to discuss
              your specific logistics challenges and discover how we can help.
            </p>
            <Link href="/contact">
              <Button
                size="lg"
                className="bg-white text-[#FF7F00] hover:bg-gray-50 font-bold px-8 py-7 text-lg rounded-xl shadow-xl group"
              >
                Contact Our Team
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={20} />
              </Button>
            </Link>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
