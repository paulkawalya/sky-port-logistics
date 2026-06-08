"use client";

import Link from "next/link";
import {
  ArrowRight,
  Shield,
  Clock,
  Globe,
  HeadphonesIcon,
  Anchor,
  CheckCircle,
  Target,
  Eye,
  Heart,
  Zap,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import AnimatedSection from "@/components/AnimatedSection";

const milestones = [
  { year: "2010", title: "Founded", description: "Sky Port Logistics established in Los Angeles with a vision to simplify global shipping." },
  { year: "2014", title: "50 Countries", description: "Expanded operations to 50 countries across 4 continents with regional offices." },
  { year: "2018", title: "100k Shipments", description: "Reached the milestone of 100,000 successful shipments handled per year." },
  { year: "2022", title: "150+ Countries", description: "Global network expanded to over 150 countries with 64+ offices worldwide." },
  { year: "2026", title: "Industry Leader", description: "Recognized as the #1 logistics partner by Global Freight Insights annual report." },
];

const values = [
  { icon: Shield, title: "Integrity", description: "We operate with complete transparency and ethical standards in every transaction." },
  { icon: Zap, title: "Innovation", description: "We continuously invest in technology and processes to deliver smarter solutions." },
  { icon: Heart, title: "Customer Focus", description: "Every decision we make starts with the question: how does this benefit our customers?" },
  { icon: Globe, title: "Global Mindset", description: "We think globally while acting locally, respecting cultures and regulations everywhere." },
];

export default function AboutPage() {
  return (
    <div className="pt-20">
      {/* Hero */}
      <section className="relative py-28 overflow-hidden">
        <div className="absolute inset-0">
          <img
            src="/warehouse.png"
            alt="Sky Port Logistics"
            className="w-full h-full object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-br from-[#003D66]/90 via-[#005B96]/80 to-[#003D66]/70" />
        </div>
        <div className="absolute inset-0 grid-pattern opacity-10" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection>
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2 mb-8">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              About Us
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">
              Delivering Trust,{" "}
              <span className="bg-gradient-to-r from-[#FF7F00] to-[#FFA040] bg-clip-text text-transparent">Connecting</span> the World
            </h1>
            <p className="text-white/70 text-lg max-w-2xl leading-relaxed">
              For over 25 years, Sky Port Logistics has been the trusted partner
              for businesses seeking seamless, reliable, and transparent global
              shipping solutions.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Company Story */}
      <section className="py-28 bg-white relative">
        <div className="absolute top-0 right-0 w-[500px] h-[500px] bg-[#005B96]/3 rounded-full blur-[120px]" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-20 items-center">
            <AnimatedSection>
              <div className="relative">
                <div className="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#005B96]/10">
                  <img
                    src="/warehouse.png"
                    alt="Sky Port Operations"
                    className="w-full h-[480px] object-cover"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-[#003D66]/40 to-transparent" />
                </div>
                <div className="absolute -bottom-8 -right-4 sm:-right-8 bg-gradient-to-br from-[#FF7F00] to-[#CC6600] text-white rounded-2xl p-6 shadow-xl shadow-[#FF7F00]/20 hidden sm:block">
                  <div className="text-4xl font-extrabold">25+</div>
                  <div className="text-sm font-medium text-white/80">Years Experience</div>
                </div>
                <div className="absolute -top-6 -left-6 bg-white rounded-2xl p-5 shadow-xl shadow-[#005B96]/10 hidden sm:block">
                  <Anchor className="text-[#FF7F00]" size={32} />
                </div>
                <div className="absolute -bottom-4 -left-4 w-24 h-24 border-l-2 border-b-2 border-[#FF7F00]/30 rounded-bl-3xl" />
              </div>
            </AnimatedSection>

            <AnimatedSection delay={0.2}>
              <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase">
                <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
                Our Story
              </span>
              <h2 className="text-4xl sm:text-5xl font-extrabold text-[#003D66] mt-5 mb-8 tracking-tight leading-tight">
                From Regional Forwarder to Global Powerhouse
              </h2>
              <p className="text-gray-500 leading-relaxed mb-5 text-[15px]">
                Founded over 25 years ago in Los Angeles, Sky Port Logistics began
                as a regional freight forwarder with a simple mission: to make
                international shipping seamless, transparent, and reliable for
                businesses of every size.
              </p>
              <p className="text-gray-500 leading-relaxed mb-5 text-[15px]">
                Through strategic investments in technology, talent, and global
                partnerships, we&apos;ve grown into a logistics powerhouse operating
                across six continents. Today, we handle over 50,000 shipments per
                year for 2,500+ clients, with a 99% on-time delivery rate that
                speaks for itself.
              </p>
              <p className="text-gray-500 leading-relaxed mb-8 text-[15px]">
                Our dedicated teams work around the clock to anticipate challenges,
                clear customs swiftly, and deliver on our promises — every single
                time. We don&apos;t just move cargo; we build lasting partnerships
                that drive business growth.
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
            </AnimatedSection>
          </div>
        </div>
      </section>

      {/* Mission / Vision / Values */}
      <section className="py-28 bg-gray-50 relative overflow-hidden">
        <div className="absolute top-0 left-0 w-[400px] h-[400px] bg-[#005B96]/3 rounded-full blur-[120px]" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              What Drives Us
            </span>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Mission, Vision &{" "}
              <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Values</span>
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <AnimatedSection delay={0}>
              <div className="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 card-border-glow h-full text-center">
                <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-16 h-16 flex items-center justify-center mx-auto mb-6 shadow-md shadow-[#005B96]/20 group-hover:scale-110 transition-all duration-300">
                  <Target className="text-white" size={28} />
                </div>
                <h3 className="text-xl font-bold text-[#003D66] mb-3">Our Mission</h3>
                <p className="text-gray-500 leading-relaxed text-[15px]">
                  To make international shipping seamless, transparent, and
                  reliable for businesses of every size, powered by innovation
                  and dedicated people.
                </p>
              </div>
            </AnimatedSection>

            <AnimatedSection delay={0.1}>
              <div className="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 card-border-glow h-full text-center">
                <div className="bg-gradient-to-br from-[#FF7F00] to-[#CC6600] rounded-xl w-16 h-16 flex items-center justify-center mx-auto mb-6 shadow-md shadow-[#FF7F00]/20 group-hover:scale-110 transition-all duration-300">
                  <Eye className="text-white" size={28} />
                </div>
                <h3 className="text-xl font-bold text-[#003D66] mb-3">Our Vision</h3>
                <p className="text-gray-500 leading-relaxed text-[15px]">
                  To be the world&apos;s most trusted logistics partner, setting the
                  standard for speed, security, and sustainability in global
                  supply chain management.
                </p>
              </div>
            </AnimatedSection>

            <AnimatedSection delay={0.2}>
              <div className="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 card-border-glow h-full text-center">
                <div className="bg-gradient-to-br from-[#005B96] to-[#003D66] rounded-xl w-16 h-16 flex items-center justify-center mx-auto mb-6 shadow-md shadow-[#005B96]/20 group-hover:scale-110 transition-all duration-300">
                  <Heart className="text-white" size={28} />
                </div>
                <h3 className="text-xl font-bold text-[#003D66] mb-3">Our Values</h3>
                <p className="text-gray-500 leading-relaxed text-[15px]">
                  Integrity, innovation, customer focus, and a global mindset
                  guide every decision we make and every shipment we handle.
                </p>
              </div>
            </AnimatedSection>
          </div>

          {/* Core Values Grid */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {values.map((value, idx) => (
              <AnimatedSection key={value.title} delay={idx * 0.1}>
                <div className="flex items-start gap-4 bg-white rounded-2xl p-5 shadow-sm border border-gray-100/80">
                  <div className="bg-[#005B96]/8 rounded-xl p-3 flex-shrink-0">
                    <value.icon className="text-[#005B96]" size={24} />
                  </div>
                  <div>
                    <h4 className="font-bold text-[#003D66] mb-1">{value.title}</h4>
                    <p className="text-gray-500 text-sm leading-relaxed">{value.description}</p>
                  </div>
                </div>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* Timeline */}
      <section className="py-28 bg-white relative">
        <div className="absolute inset-0 diagonal-stripes" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Our Journey
            </span>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Key Milestones
            </h2>
          </AnimatedSection>

          <div className="relative">
            {/* Vertical line */}
            <div className="absolute left-4 sm:left-1/2 top-0 bottom-0 w-0.5 bg-[#005B96]/20 sm:-translate-x-0.5" />

            <div className="space-y-12">
              {milestones.map((milestone, idx) => (
                <AnimatedSection key={milestone.year} delay={idx * 0.1}>
                  <div className={`relative flex items-center gap-8 ${idx % 2 === 0 ? "sm:flex-row" : "sm:flex-row-reverse"}`}>
                    <div className={`flex-1 ${idx % 2 === 0 ? "sm:text-right" : "sm:text-left"} hidden sm:block`}>
                      {idx % 2 === 0 ? (
                        <div className="inline-block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100/80 card-border-glow">
                          <div className="p-6">
                            <div className="text-[#FF7F00] font-extrabold text-2xl mb-1">{milestone.year}</div>
                            <h3 className="text-lg font-bold text-[#003D66] mb-2">{milestone.title}</h3>
                            <p className="text-gray-500 text-sm">{milestone.description}</p>
                          </div>
                        </div>
                      ) : (
                        <div />
                      )}
                    </div>

                    {/* Dot */}
                    <div className="absolute left-4 sm:left-1/2 w-4 h-4 bg-[#FF7F00] rounded-full border-4 border-white shadow-lg sm:-translate-x-2 z-10" />

                    <div className={`flex-1 ${idx % 2 === 1 ? "sm:text-left" : "sm:text-left"}`}>
                      <div className="inline-block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100/80 card-border-glow sm:hidden">
                        <div className="p-6">
                          <div className="text-[#FF7F00] font-extrabold text-2xl mb-1">{milestone.year}</div>
                          <h3 className="text-lg font-bold text-[#003D66] mb-2">{milestone.title}</h3>
                          <p className="text-gray-500 text-sm">{milestone.description}</p>
                        </div>
                      </div>
                      {idx % 2 === 1 && (
                        <div className="hidden sm:inline-block bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100/80 card-border-glow">
                          <div className="p-6">
                            <div className="text-[#FF7F00] font-extrabold text-2xl mb-1">{milestone.year}</div>
                            <h3 className="text-lg font-bold text-[#003D66] mb-2">{milestone.title}</h3>
                            <p className="text-gray-500 text-sm">{milestone.description}</p>
                          </div>
                        </div>
                      )}
                    </div>
                  </div>
                </AnimatedSection>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Network */}
      <section id="network" className="py-28 bg-gray-50 relative">
        <div className="absolute inset-0 grid-pattern opacity-30" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Our Reach
            </span>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Global Network,{" "}
              <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Local Expertise</span>
            </h2>
            <p className="mt-5 text-gray-500 max-w-2xl mx-auto text-lg leading-relaxed">
              With strategically placed hubs on every continent, we navigate
              local regulations and infrastructure to deliver seamless logistics
              anywhere in the world.
            </p>
          </AnimatedSection>

          <AnimatedSection>
            <div className="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#005B96]/10 group">
              <img
                src="/global-network.png"
                alt="Global logistics network"
                className="w-full h-auto group-hover:scale-105 transition-transform duration-700"
              />
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
                      <div className="text-[#FF7F00] font-bold text-lg mb-1 group-hover/item:text-[#FFA040] transition-colors">
                        {item.region}
                      </div>
                      <div className="text-white/60 text-sm">{item.offices}</div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </AnimatedSection>
        </div>
      </section>

      {/* Team Placeholder */}
      <section id="careers" className="py-28 bg-white relative">
        <div className="absolute inset-0 dot-pattern" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Our Team
            </span>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              The People Behind{" "}
              <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Sky Port</span>
            </h2>
            <p className="mt-5 text-gray-500 max-w-2xl mx-auto text-lg leading-relaxed">
              Our diverse team of 3,000+ logistics professionals spans the globe,
              bringing deep local expertise and a shared commitment to excellence.
            </p>
          </AnimatedSection>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {[
              { name: "James Mitchell", role: "CEO & Founder", initials: "JM" },
              { name: "Sarah Kim", role: "Chief Operations Officer", initials: "SK" },
              { name: "Carlos Mendez", role: "VP, Air Freight", initials: "CM" },
              { name: "Aisha Patel", role: "VP, Ocean Freight", initials: "AP" },
            ].map((member, idx) => (
              <AnimatedSection key={member.name} delay={idx * 0.1}>
                <div className="group bg-white rounded-2xl p-7 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 card-border-glow text-center">
                  <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-md shadow-[#005B96]/20 group-hover:scale-110 transition-all duration-300">
                    <span className="text-white font-bold text-xl">{member.initials}</span>
                  </div>
                  <h3 className="font-bold text-[#003D66] mb-1">{member.name}</h3>
                  <p className="text-gray-500 text-sm">{member.role}</p>
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
              Want to Join Our Team?
            </h2>
            <p className="text-white/80 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
              We&apos;re always looking for talented individuals who share our passion
              for logistics and commitment to excellence. Explore career opportunities.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link href="/contact">
                <Button
                  size="lg"
                  className="bg-white text-[#FF7F00] hover:bg-gray-50 font-bold px-8 py-7 text-lg rounded-xl shadow-xl group"
                >
                  Contact Us
                  <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={20} />
                </Button>
              </Link>
              <Link href="/services">
                <Button
                  size="lg"
                  variant="outline"
                  className="border-white/25 text-white hover:bg-white/10 font-bold px-8 py-7 text-lg rounded-xl backdrop-blur-sm"
                >
                  Our Services
                </Button>
              </Link>
            </div>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
