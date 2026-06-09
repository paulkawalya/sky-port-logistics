"use client";

import { useState } from "react";
import {
  ArrowRight,
  MapPin,
  Phone,
  Mail,
  Clock,
  Send,
  Globe,
  Shield,
  HeadphonesIcon,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import AnimatedSection from "@/components/AnimatedSection";

const offices = [
  { city: "Kampala (HQ)", country: "Uganda", address: "Crown House, Kampala Road, Level 3", phone: "+256 782 843531" },
  { city: "Mombasa", country: "Kenya", address: "Port Office, Mombasa Port", phone: "+254 700 123 456" },
  { city: "Dar es Salaam", country: "Tanzania", address: "Port House, Dar es Salaam Harbour", phone: "+255 700 123 456" },
  { city: "Kigali", country: "Rwanda", address: "Kigali Logistics Park", phone: "+250 730 123 456" },
  { city: "Nairobi", country: "Kenya", address: "JKIA Cargo Centre", phone: "+254 700 456 789" },
  { city: "Dubai", country: "UAE", address: "DIFC, Gate Tower, Office 1502", phone: "+971 4 555 7800" },
];

export default function ContactPage() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    company: "",
    service: "",
    message: "",
  });

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
              Get In Touch
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">
              Contact{" "}
              <span className="bg-gradient-to-r from-[#FF7F00] to-[#FFA040] bg-clip-text text-transparent">Us</span>
            </h1>
            <p className="text-white/70 text-lg max-w-2xl mx-auto leading-relaxed">
              Have a shipment need or a question? Our team is ready to help you
              find the perfect logistics solution, 24/7.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Contact Form + Info */}
      <section className="py-28 bg-white relative">
        <div className="absolute inset-0 dot-pattern" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-5 gap-12">
            {/* Contact Info */}
            <AnimatedSection className="lg:col-span-2">
              <div className="space-y-6">
                <div>
                  <h2 className="text-3xl font-extrabold text-[#003D66] mb-2 tracking-tight">
                    Let&apos;s Start a Conversation
                  </h2>
                  <p className="text-gray-500 text-[15px] leading-relaxed">
                    Fill out the form and our team will respond within 2 business
                    hours. For urgent requests, call us directly.
                  </p>
                </div>

                {[
                  {
                    icon: MapPin,
                    title: "Head Office",
                    lines: [
                      "Crown House, Kampala Road, Level 3",
                      "Kampala, Uganda",
                    ],
                  },
                  {
                    icon: Phone,
                    title: "WhatsApp / Phone",
                    lines: ["+256 782 843531"],
                  },
                  {
                    icon: Mail,
                    title: "Email",
                    lines: [
                      "info@skyportlogisticsltd.com",
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
                  <div key={item.title} className="bg-gray-50 rounded-xl p-4 border border-gray-100/80">
                    <div className="flex gap-4">
                      <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl p-3 h-fit shadow-md shadow-[#005B96]/20">
                        <item.icon className="text-white" size={20} />
                      </div>
                      <div>
                        <div className="font-semibold text-[#003D66] text-sm">
                          {item.title}
                        </div>
                        {item.lines.map((line) => (
                          <div key={line} className="text-gray-500 text-sm">
                            {line}
                          </div>
                        ))}
                      </div>
                    </div>
                  </div>
                ))}

                <div className="pt-4">
                  <h3 className="font-semibold text-[#003D66] mb-3">Why Choose Sky Port</h3>
                  <div className="space-y-3">
                    {[
                      { icon: Shield, text: "ISO 9001 & IATA Certified" },
                      { icon: Globe, text: "150+ Countries Covered" },
                      { icon: HeadphonesIcon, text: "24/7 Multilingual Support" },
                    ].map((item) => (
                      <div key={item.text} className="flex items-center gap-3">
                        <item.icon className="text-[#FF7F00]" size={18} />
                        <span className="text-gray-500 text-sm">{item.text}</span>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </AnimatedSection>

            {/* Contact Form */}
            <AnimatedSection className="lg:col-span-3" delay={0.2}>
              <div className="bg-white rounded-2xl shadow-sm border border-gray-100/80 overflow-hidden">
                <div className="p-6 sm:p-8">
                  <h3 className="text-xl font-bold text-[#003D66] mb-6">
                    Request a Free Quote
                  </h3>
                  <form
                    onSubmit={(e) => {
                      e.preventDefault();
                      alert("Thank you! We will contact you within 2 business hours.");
                    }}
                    className="space-y-5"
                  >
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                      <div>
                        <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">
                          Full Name *
                        </label>
                        <Input
                          placeholder="John Doe"
                          value={formData.name}
                          onChange={(e) =>
                            setFormData({ ...formData, name: e.target.value })
                          }
                          className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30"
                          required
                        />
                      </div>
                      <div>
                        <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">
                          Email Address *
                        </label>
                        <Input
                          type="email"
                          placeholder="john@company.com"
                          value={formData.email}
                          onChange={(e) =>
                            setFormData({ ...formData, email: e.target.value })
                          }
                          className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30"
                          required
                        />
                      </div>
                    </div>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                      <div>
                        <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">
                          Phone Number
                        </label>
                        <Input
                          placeholder="+1 (555) 000-0000"
                          value={formData.phone}
                          onChange={(e) =>
                            setFormData({ ...formData, phone: e.target.value })
                          }
                          className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30"
                        />
                      </div>
                      <div>
                        <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">
                          Company Name
                        </label>
                        <Input
                          placeholder="Your Company"
                          value={formData.company}
                          onChange={(e) =>
                            setFormData({ ...formData, company: e.target.value })
                          }
                          className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30"
                        />
                      </div>
                    </div>
                    <div>
                      <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">
                        Service Needed
                      </label>
                      <Input
                        placeholder="Air Freight, Ocean Freight, Warehousing, etc."
                        value={formData.service}
                        onChange={(e) =>
                          setFormData({ ...formData, service: e.target.value })
                        }
                        className="h-12 rounded-xl border-gray-200 focus:ring-[#FF7F00]/30"
                      />
                    </div>
                    <div>
                      <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 block">
                        Message *
                      </label>
                      <Textarea
                        placeholder="Tell us about your shipping needs, cargo type, origin/destination, volume, timeline..."
                        rows={5}
                        value={formData.message}
                        onChange={(e) =>
                          setFormData({ ...formData, message: e.target.value })
                        }
                        className="rounded-xl border-gray-200 focus:ring-[#FF7F00]/30"
                        required
                      />
                    </div>
                    <Button
                      type="submit"
                      className="w-full bg-[#FF7F00] hover:bg-[#CC6600] text-white font-bold h-12 rounded-xl text-lg shadow-lg shadow-[#FF7F00]/25 btn-glow group"
                    >
                      Send Message
                      <Send className="ml-2 group-hover:translate-x-1 transition-transform" />
                    </Button>
                    <p className="text-gray-400 text-xs text-center">
                      By submitting this form, you agree to our Privacy Policy and Terms of Service.
                    </p>
                  </form>
                </div>
              </div>
            </AnimatedSection>
          </div>
        </div>
      </section>

      {/* Map Placeholder */}
      <section className="bg-gray-100">
        <div className="relative h-80 bg-gradient-to-br from-[#005B96]/10 to-[#003D66]/5 flex items-center justify-center dot-pattern">
          <div className="text-center">
            <MapPin className="text-[#FF7F00] mx-auto mb-3" size={40} />
            <h3 className="text-[#003D66] font-extrabold text-xl mb-1 tracking-tight">Global Headquarters</h3>
            <p className="text-gray-500">Crown House, Kampala Road, Level 3, Uganda</p>
          </div>
        </div>
      </section>

      {/* Office Locations */}
      <section className="py-28 bg-gray-50 relative">
        <div className="absolute inset-0 grid-pattern opacity-30" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-20">
            <span className="inline-flex items-center gap-2 text-[#FF7F00] font-bold text-xs tracking-[0.25em] uppercase bg-[#FF7F00]/8 rounded-full px-5 py-2">
              <div className="w-1.5 h-1.5 bg-[#FF7F00] rounded-full" />
              Global Presence
            </span>
            <h2 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#003D66] mt-5 tracking-tight">
              Our Offices{" "}
              <span className="bg-gradient-to-r from-[#005B96] to-[#FF7F00] bg-clip-text text-transparent">Worldwide</span>
            </h2>
            <p className="mt-5 text-gray-500 max-w-2xl mx-auto text-lg leading-relaxed">
              With strategically located offices across six continents, we&apos;re
              always close to where you need us.
            </p>
          </AnimatedSection>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {offices.map((office, idx) => (
              <AnimatedSection key={office.city} delay={idx * 0.08}>
                <div className="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-400 border border-gray-100/80 card-border-glow h-full">
                  <div className="flex items-start gap-4">
                    <div className="bg-gradient-to-br from-[#005B96] to-[#0078C8] rounded-xl p-2.5 flex-shrink-0 shadow-md shadow-[#005B96]/20 group-hover:scale-110 transition-all duration-300">
                      <MapPin className="text-white" size={20} />
                    </div>
                    <div>
                      <h3 className="font-bold text-[#003D66]">
                        {office.city}
                      </h3>
                      <p className="text-[#FF7F00] text-sm font-medium">
                        {office.country}
                      </p>
                      <p className="text-gray-500 text-sm mt-2">
                        {office.address}
                      </p>
                      <p className="text-gray-400 text-sm mt-1">
                        {office.phone}
                      </p>
                    </div>
                  </div>
                </div>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
