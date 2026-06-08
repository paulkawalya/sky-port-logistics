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
      <section className="relative py-24 overflow-hidden">
        <div className="absolute inset-0">
          <img
            src="/hero-bg.png"
            alt="Track your shipment"
            className="w-full h-full object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-r from-[#003D66]/90 via-[#005B96]/80 to-[#003D66]/70" />
        </div>
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <AnimatedSection>
            <Package className="text-[#FF7F00] mx-auto mb-6" size={48} />
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
              Track & <span className="text-[#FF7F00]">Trace</span>
            </h1>
            <p className="text-white/80 text-lg max-w-2xl mx-auto mb-10">
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
                className="flex-1 h-14 bg-white/10 border-white/20 text-white placeholder:text-white/50 text-lg rounded-full px-6 focus:ring-[#FF7F00]"
              />
              <Button
                type="submit"
                className="h-14 bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold px-8 rounded-full shadow-lg text-lg group"
              >
                Track Now
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
              </Button>
            </form>
          </AnimatedSection>
        </div>
      </section>

      {/* Tracking Features */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-16">
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              Tracking Features
            </span>
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
              Real-Time Visibility
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
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
                <Card className="border-0 shadow-md hover:shadow-xl transition-all duration-300 h-full">
                  <CardContent className="p-6 text-center">
                    <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl w-16 h-16 flex items-center justify-center mx-auto mb-5">
                      <feature.icon className="text-white" size={28} />
                    </div>
                    <h3 className="text-xl font-bold text-[#005B96] mb-3">
                      {feature.title}
                    </h3>
                    <p className="text-gray-600 leading-relaxed">
                      {feature.description}
                    </p>
                  </CardContent>
                </Card>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* How It Works */}
      <section className="py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-16">
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              How It Works
            </span>
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
              Track in 3 Simple Steps
            </h2>
          </AnimatedSection>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {trackingSteps.map((step, idx) => (
              <AnimatedSection key={step.title} delay={idx * 0.15}>
                <div className="relative text-center">
                  <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                    <step.icon className="text-white" size={36} />
                  </div>
                  <div className="absolute top-10 left-0 right-0 hidden md:block">
                    {idx < trackingSteps.length - 1 && (
                      <div className="border-t-2 border-dashed border-[#005B96]/20 w-full" />
                    )}
                  </div>
                  <div className="bg-[#FF7F00] text-white rounded-full w-8 h-8 flex items-center justify-center mx-auto -mt-2 mb-4 text-sm font-bold">
                    {idx + 1}
                  </div>
                  <h3 className="text-lg font-bold text-[#005B96] mb-2">
                    {step.title}
                  </h3>
                  <p className="text-gray-600 text-sm leading-relaxed">
                    {step.description}
                  </p>
                </div>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>

      {/* Sample Tracking Result */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-12">
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              Sample Result
            </span>
            <h2 className="text-3xl sm:text-4xl font-bold text-[#005B96] mt-3">
              What You&apos;ll See
            </h2>
            <p className="text-gray-600 mt-4 max-w-lg mx-auto">
              Here&apos;s an example of the detailed tracking information you&apos;ll
              receive for every shipment.
            </p>
          </AnimatedSection>

          <AnimatedSection>
            <Card className="border-0 shadow-xl overflow-hidden">
              <CardContent className="p-0">
                {/* Tracking Header */}
                <div className="bg-gradient-to-r from-[#005B96] to-[#003D66] p-6 sm:p-8">
                  <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                      <div className="flex items-center gap-3 mb-2">
                        <Ship className="text-white" size={24} />
                        <span className="text-white/80 text-sm font-medium">{mockTrackingResult.service}</span>
                      </div>
                      <h3 className="text-white font-bold text-xl">
                        {mockTrackingResult.trackingId}
                      </h3>
                      <div className="flex items-center gap-2 mt-2">
                        <span className="bg-[#FF7F00] text-white text-xs font-bold px-3 py-1 rounded-full">
                          {mockTrackingResult.status}
                        </span>
                      </div>
                    </div>
                    <div className="flex items-center gap-8">
                      <div className="text-center">
                        <div className="text-white/60 text-xs mb-1">Origin</div>
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
                        <div className="text-white/60 text-xs mb-1">Destination</div>
                        <div className="text-white font-semibold">{mockTrackingResult.destination}</div>
                      </div>
                    </div>
                  </div>
                  <div className="mt-4 text-white/80 text-sm">
                    Estimated Delivery: <span className="text-white font-semibold">{mockTrackingResult.estimatedDelivery}</span>
                  </div>
                </div>

                {/* Timeline */}
                <div className="p-6 sm:p-8">
                  <h4 className="font-bold text-[#005B96] mb-6">Shipment Timeline</h4>
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
                            <span className="font-semibold text-[#005B96] text-sm">
                              {event.event}
                            </span>
                            {event.status === "current" && (
                              <span className="bg-[#FF7F00]/10 text-[#FF7F00] text-xs font-bold px-2 py-0.5 rounded-full">
                                Current
                              </span>
                            )}
                          </div>
                          <div className="text-gray-500 text-xs mt-1">
                            {event.date} {event.time !== "—" && `• ${event.time}`} • {event.location}
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              </CardContent>
            </Card>
          </AnimatedSection>
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
              Need Help Tracking?
            </h2>
            <p className="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
              Can&apos;t find your tracking number or need assistance? Our support
              team is available 24/7 to help.
            </p>
            <Link href="/contact">
              <Button
                size="lg"
                className="bg-white text-[#FF7F00] hover:bg-gray-100 font-bold px-8 py-6 text-lg rounded-full shadow-xl group"
              >
                Contact Support
                <ArrowRight className="ml-2 group-hover:translate-x-1 transition-transform" />
              </Button>
            </Link>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
