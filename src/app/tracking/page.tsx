"use client";

import { useState } from "react";
import Link from "next/link";
import {
  ArrowRight,
  Package,
  Clock,
  Shield,
  Globe,
  CheckCircle,
  MapPin,
  Truck,
  Plane,
  Ship,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import AnimatedSection from "@/components/AnimatedSection";

const trackingSteps = [
  { icon: Package, title: "Enter Tracking Number", description: "Input your unique tracking ID provided when your shipment was booked." },
  { icon: Globe, title: "Real-Time Location", description: "See exactly where your shipment is on a live map with GPS-accurate positioning." },
  { icon: CheckCircle, title: "Delivery Confirmation", description: "Receive instant notifications when your shipment arrives at its destination." },
];

const mockTrackingResult = {
  trackingId: "SKP-2026-87432",
  status: "In Transit",
  origin: "Shanghai, China",
  destination: "Los Angeles, USA",
  service: "Ocean Freight",
  estimatedDelivery: "March 22, 2026",
  timeline: [
    { date: "Mar 10, 2026", time: "09:30 AM", event: "Shipment picked up from warehouse", location: "Shanghai, China", status: "completed" },
    { date: "Mar 10, 2026", time: "02:15 PM", event: "Customs clearance completed (export)", location: "Shanghai Port, China", status: "completed" },
    { date: "Mar 11, 2026", time: "06:00 AM", event: "Loaded onto vessel - COSCO Shipping", location: "Shanghai Port, China", status: "completed" },
    { date: "Mar 15, 2026", time: "11:45 AM", event: "Vessel in transit - Pacific Ocean", location: "Mid-Pacific", status: "current" },
    { date: "Mar 20, 2026", time: "—", event: "Vessel arrival at port", location: "Port of Los Angeles, USA", status: "pending" },
    { date: "Mar 21, 2026", time: "—", event: "Customs clearance (import)", location: "Los Angeles, USA", status: "pending" },
    { date: "Mar 22, 2026", time: "—", event: "Final delivery", location: "Los Angeles, USA", status: "pending" },
  ],
};

export default function TrackingPage() {
  const [trackingId, setTrackingId] = useState("");
  const [showResult, setShowResult] = useState(false);

  const handleTrack = (e: React.FormEvent) => {
    e.preventDefault();
    setShowResult(true);
  };

  return (
    <div className="pt-20">
      {/* Hero */}
      <section className="relative py-28 overflow-hidden">
        <div className="absolute inset-0">
          <img
            src="/hero-bg.png"
            alt="Track your shipment"
            className="w-full h-full object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-br from-[#003D66]/90 via-[#005B96]/80 to-[#003D66]/70" />
        </div>
        <div className="absolute inset-0 grid-pattern opacity-10" />
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <AnimatedSection>
            <div className="w-16 h-16 rounded-2xl bg-[#FF7F00]/15 flex items-center justify-center mx-auto mb-8 border border-[#FF7F00]/20">
              <Package className="text-[#FF7F00]" size={32} />
            </div>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">
              Track &{" "}
              <span className="bg-gradient-to-r from-[#FF7F00] to-[#FFA040] bg-clip-text text-transparent">Trace</span>
            </h1>
            <p className="text-white/70 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
              Enter your tracking number to get real-time updates on your
              shipment&apos;s location, status, and estimated delivery time.
            </p>
            <form
              onSubmit={handleTrack}
              className="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto"
            >
              <Input
                placeholder="Enter tracking number (e.g., SKP-2026-87432)"
                value={trackingId}
                onChange={(e) => setTrackingId(e.target.value)}
                className="flex-1 h-14 bg-white/8 border-white/15 text-white placeholder:text-white/30 text-lg rounded-xl px-6 backdrop-blur-sm focus:ring-[#FF7F00]/50"
              />
              <Button
                type="submit"
                className="h-14 bg-[#FF7F00] hover:bg-[#CC6600] text-white font-bold px-8 rounded-xl shadow-lg shadow-[#FF7F00]/25 text-lg btn-glow group"
              >
                Track Now
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={18} />
              </Button>
            </form>
          </AnimatedSection>
        </div>
      </section>

      {/* Tracking Features */}
      <section className="py-28 bg-white relative">
        <div className="absolute inset-0 dot-pattern" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Tracking Features
            </span>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Real-Time{" "}
              <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Visibility</span>
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {[
              {
                icon: Clock,
                title: "Real-Time Updates",
                description: "GPS-accurate tracking with automatic status updates every 15 minutes. Know exactly where your shipment is at all times.",
              },
              {
                icon: Globe,
                title: "Multi-Carrier Support",
                description: "Track shipments across all carriers and modes — air, ocean, road — in a single unified dashboard. No more switching between systems.",
              },
              {
                icon: Shield,
                title: "Instant Notifications",
                description: "Receive email and SMS alerts for key milestones: pickup, customs clearance, departure, arrival, and delivery confirmation.",
              },
            ].map((feature, idx) => (
              <AnimatedSection key={feature.title} delay={idx * 0.1}>
                <div className="group bg-white rounded-2xl p-7 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 card-border-glow h-full">
                  <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-16 h-16 flex items-center justify-center mb-6 shadow-md shadow-[#005B96]/20 group-hover:scale-110 transition-all duration-300">
                    <feature.icon className="text-white" size={28} />
                  </div>
                  <h3 className="text-xl font-bold text-[#003D66] mb-3">
                    {feature.title}
                  </h3>
                  <p className="text-gray-500 leading-relaxed text-[15px]">
                    {feature.description}
                  </p>
                </div>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* How It Works */}
      <section className="py-28 bg-gray-50 relative">
        <div className="absolute inset-0 grid-pattern opacity-30" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              How It Works
            </span>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Track in 3 Simple Steps
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {trackingSteps.map((step, idx) => (
              <AnimatedSection key={step.title} delay={idx * 0.15}>
                <div className="relative text-center">
                  <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-20 h-20 flex items-center justify-center mx-auto mb-6 shadow-md shadow-[#005B96]/20">
                    <step.icon className="text-white" size={36} />
                  </div>
                  <div className="absolute top-10 left-0 right-0 hidden md:block">
                    {idx < trackingSteps.length - 1 && (
                      <div className="border-t-2 border-dashed border-[#005B96]/20 w-full" />
                    )}
                  </div>
                  <div className="bg-[#FF7F00] text-white rounded-xl w-8 h-8 flex items-center justify-center mx-auto -mt-2 mb-4 text-sm font-bold shadow-md shadow-[#FF7F00]/20">
                    {idx + 1}
                  </div>
                  <h3 className="text-lg font-bold text-[#003D66] mb-2">
                    {step.title}
                  </h3>
                  <p className="text-gray-500 text-sm leading-relaxed">
                    {step.description}
                  </p>
                </div>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* Sample Tracking Result */}
      <section className="py-28 bg-white relative">
        <div className="absolute inset-0 diagonal-stripes" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-12">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Sample Result
            </span>
            <h2 className="text-4xl sm:text-5xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              What You&apos;ll See
            </h2>
            <p className="text-gray-500 mt-5 max-w-lg mx-auto leading-relaxed">
              Here&apos;s an example of the detailed tracking information you&apos;ll
              receive for every shipment.
            </p>
          </AnimatedSection>

          <AnimatedSection>
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100/80 overflow-hidden">
              {/* Tracking Header */}
              <div className="bg-gradient-to-br from-[#003D66] via-[#005B96] to-[#003D66] p-6 sm:p-8 relative">
                <div className="absolute inset-0 grid-pattern opacity-10" />
                <div className="relative z-10">
                  <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                      <div className="flex items-center gap-3 mb-2">
                        <Ship className="text-white" size={24} />
                        <span className="text-white/60 text-sm font-medium">{mockTrackingResult.service}</span>
                      </div>
                      <h3 className="text-white font-extrabold text-xl tracking-tight">
                        {mockTrackingResult.trackingId}
                      </h3>
                      <div className="flex items-center gap-2 mt-2">
                        <span className="bg-[#FF7F00] text-white text-xs font-bold px-3 py-1 rounded-full shadow-md shadow-[#FF7F00]/30">
                          {mockTrackingResult.status}
                        </span>
                      </div>
                    </div>
                    <div className="flex items-center gap-8">
                      <div className="text-center">
                        <div className="text-white/50 text-xs mb-1">Origin</div>
                        <div className="text-white font-semibold">{mockTrackingResult.origin}</div>
                      </div>
                      <div className="flex items-center gap-2">
                        <div className="w-2 h-2 bg-[#FF7F00] rounded-full" />
                        <div className="w-20 sm:w-32 h-0.5 bg-gradient-to-r from-[#FF7F00] to-white/30" />
                        <Plane className="text-white/40" size={16} />
                        <div className="w-20 sm:w-32 h-0.5 bg-white/20" />
                        <div className="w-2 h-2 bg-white/30 rounded-full" />
                      </div>
                      <div className="text-center">
                        <div className="text-white/50 text-xs mb-1">Destination</div>
                        <div className="text-white font-semibold">{mockTrackingResult.destination}</div>
                      </div>
                    </div>
                  </div>
                  <div className="mt-4 text-white/70 text-sm">
                    Estimated Delivery: <span className="text-white font-semibold">{mockTrackingResult.estimatedDelivery}</span>
                  </div>
                </div>
              </div>

              {/* Timeline */}
              <div className="p-6 sm:p-8">
                <h4 className="font-bold text-[#003D66] mb-6">Shipment Timeline</h4>
                <div className="space-y-0">
                  {mockTrackingResult.timeline.map((event, idx) => (
                    <div key={idx} className="flex gap-4">
                      <div className="flex flex-col items-center">
                        <div
                          className={`w-4 h-4 rounded-full border-2 flex-shrink-0 ${
                            event.status === "completed"
                              ? "bg-[#005B96] border-[#005B96]"
                              : event.status === "current"
                              ? "bg-[#FF7F00] border-[#FF7F00] ring-4 ring-[#FF7F00]/20"
                              : "bg-white border-gray-300"
                          }`}
                        />
                        {idx < mockTrackingResult.timeline.length - 1 && (
                          <div
                            className={`w-0.5 h-12 ${
                              event.status === "completed" ? "bg-[#005B96]" : "bg-gray-200"
                            }`}
                          />
                        )}
                      </div>
                      <div className="pb-6">
                        <div className="flex items-center gap-2">
                          <span className="font-semibold text-[#003D66] text-sm">
                            {event.event}
                          </span>
                          {event.status === "current" && (
                            <span className="bg-[#FF7F00]/10 text-[#FF7F00] text-xs font-bold px-2 py-0.5 rounded-full">
                              Current
                            </span>
                          )}
                        </div>
                        <div className="text-gray-400 text-xs mt-1">
                          {event.date} {event.time !== "—" && `• ${event.time}`} • {event.location}
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </AnimatedSection>
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
              Need Help Tracking?
            </h2>
            <p className="text-white/80 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
              Can&apos;t find your tracking number or need assistance? Our support
              team is available 24/7 to help.
            </p>
            <Link href="/contact">
              <Button
                size="lg"
                className="bg-white text-[#FF7F00] hover:bg-gray-50 font-bold px-8 py-7 text-lg rounded-xl shadow-xl group"
              >
                Contact Support
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" size={20} />
              </Button>
            </Link>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
