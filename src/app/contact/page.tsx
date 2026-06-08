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
  { city: "Los Angeles (HQ)", country: "USA", address: "1200 Harbor Blvd, Suite 800", phone: "+1 (800) 557-1234" },
  { city: "London", country: "UK", address: "45 Canary Wharf, Level 12", phone: "+44 20 7946 0958" },
  { city: "Singapore", country: "Singapore", address: "8 Marina Bay, Tower 3", phone: "+65 6100 4567" },
  { city: "Shanghai", country: "China", address: "888 Lujiazui, Floor 28", phone: "+86 21 6100 8888" },
  { city: "Dubai", country: "UAE", address: "DIFC, Gate Tower, Office 1502", phone: "+971 4 555 7800" },
  { city: "São Paulo", country: "Brazil", address: "Av. Paulista 1500, Suite 1205", phone: "+55 11 3555 8900" },
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
              Get In Touch
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
              Contact <span className="text-[#FF7F00]">Us</span>
            </h1>
            <p className="text-white/80 text-lg max-w-2xl mx-auto">
              Have a shipment need or a question? Our team is ready to help you
              find the perfect logistics solution, 24/7.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Contact Form + Info */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-5 gap-12">
            {/* Contact Info */}
            <AnimatedSection className="lg:col-span-2">
              <div className="space-y-6">
                <div>
                  <h2 className="text-2xl font-bold text-[#005B96] mb-2">
                    Let&apos;s Start a Conversation
                  </h2>
                  <p className="text-gray-600">
                    Fill out the form and our team will respond within 2 business
                    hours. For urgent requests, call us directly.
                  </p>
                </div>

                {[
                  {
                    icon: MapPin,
                    title: "Head Office",
                    lines: [
                      "Sky Port Tower, 1200 Harbor Blvd",
                      "Suite 800, Los Angeles, CA 90012",
                    ],
                  },
                  {
                    icon: Phone,
                    title: "Phone",
                    lines: ["+1 (800) 557-1234 (Toll Free)", "+1 (213) 555-6789"],
                  },
                  {
                    icon: Mail,
                    title: "Email",
                    lines: [
                      "info@skyportlogistics.com",
                      "quotes@skyportlogistics.com",
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
                  <div key={item.title} className="flex gap-4">
                    <div className="bg-[#005B96] rounded-xl p-3 h-fit">
                      <item.icon className="text-white" size={22} />
                    </div>
                    <div>
                      <div className="font-semibold text-[#005B96]">
                        {item.title}
                      </div>
                      {item.lines.map((line) => (
                        <div key={line} className="text-gray-600 text-sm">
                          {line}
                        </div>
                      ))}
                    </div>
                  </div>
                ))}

                <div className="pt-4">
                  <h3 className="font-semibold text-[#005B96] mb-3">Why Choose Sky Port</h3>
                  <div className="space-y-3">
                    {[
                      { icon: Shield, text: "ISO 9001 & IATA Certified" },
                      { icon: Globe, text: "150+ Countries Covered" },
                      { icon: HeadphonesIcon, text: "24/7 Multilingual Support" },
                    ].map((item) => (
                      <div key={item.text} className="flex items-center gap-3">
                        <item.icon className="text-[#FF7F00]" size={18} />
                        <span className="text-gray-600 text-sm">{item.text}</span>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </AnimatedSection>

            {/* Contact Form */}
            <AnimatedSection className="lg:col-span-3" delay={0.2}>
              <Card className="border-0 shadow-xl">
                <CardContent className="p-6 sm:p-8">
                  <h3 className="text-xl font-bold text-[#005B96] mb-6">
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
                        <label className="text-sm font-medium text-gray-700 mb-1 block">
                          Full Name *
                        </label>
                        <Input
                          placeholder="John Doe"
                          value={formData.name}
                          onChange={(e) =>
                            setFormData({ ...formData, name: e.target.value })
                          }
                          className="h-12 rounded-xl"
                          required
                        />
                      </div>
                      <div>
                        <label className="text-sm font-medium text-gray-700 mb-1 block">
                          Email Address *
                        </label>
                        <Input
                          type="email"
                          placeholder="john@company.com"
                          value={formData.email}
                          onChange={(e) =>
                            setFormData({ ...formData, email: e.target.value })
                          }
                          className="h-12 rounded-xl"
                          required
                        />
                      </div>
                    </div>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                      <div>
                        <label className="text-sm font-medium text-gray-700 mb-1 block">
                          Phone Number
                        </label>
                        <Input
                          placeholder="+1 (555) 000-0000"
                          value={formData.phone}
                          onChange={(e) =>
                            setFormData({ ...formData, phone: e.target.value })
                          }
                          className="h-12 rounded-xl"
                        />
                      </div>
                      <div>
                        <label className="text-sm font-medium text-gray-700 mb-1 block">
                          Company Name
                        </label>
                        <Input
                          placeholder="Your Company"
                          value={formData.company}
                          onChange={(e) =>
                            setFormData({ ...formData, company: e.target.value })
                          }
                          className="h-12 rounded-xl"
                        />
                      </div>
                    </div>
                    <div>
                      <label className="text-sm font-medium text-gray-700 mb-1 block">
                        Service Needed
                      </label>
                      <Input
                        placeholder="Air Freight, Ocean Freight, Warehousing, etc."
                        value={formData.service}
                        onChange={(e) =>
                          setFormData({ ...formData, service: e.target.value })
                        }
                        className="h-12 rounded-xl"
                      />
                    </div>
                    <div>
                      <label className="text-sm font-medium text-gray-700 mb-1 block">
                        Message *
                      </label>
                      <Textarea
                        placeholder="Tell us about your shipping needs, cargo type, origin/destination, volume, timeline..."
                        rows={5}
                        value={formData.message}
                        onChange={(e) =>
                          setFormData({ ...formData, message: e.target.value })
                        }
                        className="rounded-xl"
                        required
                      />
                    </div>
                    <Button
                      type="submit"
                      className="w-full bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold h-12 rounded-full text-lg group"
                    >
                      Send Message
                      <Send className="ml-2 group-hover:translate-x-1 transition-transform" />
                    </Button>
                    <p className="text-gray-400 text-xs text-center">
                      By submitting this form, you agree to our Privacy Policy and Terms of Service.
                    </p>
                  </form>
                </CardContent>
              </Card>
            </AnimatedSection>
          </div>
        </div>
      </section>

      {/* Map Placeholder */}
      <section className="bg-gray-100">
        <div className="relative h-80 bg-gradient-to-br from-[#005B96]/10 to-[#003D66]/5 flex items-center justify-center">
          <div className="text-center">
            <MapPin className="text-[#FF7F00] mx-auto mb-3" size={40} />
            <h3 className="text-[#005B96] font-bold text-xl mb-1">Global Headquarters</h3>
            <p className="text-gray-600">Sky Port Tower, 1200 Harbor Blvd, Los Angeles, CA 90012</p>
          </div>
        </div>
      </section>

      {/* Office Locations */}
      <section className="py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection className="text-center mb-16">
            <span className="text-[#FF7F00] font-semibold text-sm tracking-wider uppercase">
              Global Presence
            </span>
            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#005B96] mt-3">
              Our Offices Worldwide
            </h2>
            <p className="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
              With strategically located offices across six continents, we&apos;re
              always close to where you need us.
            </p>
          </AnimatedSection>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {offices.map((office, idx) => (
              <AnimatedSection key={office.city} delay={idx * 0.08}>
                <Card className="border-0 shadow-md hover:shadow-xl transition-all duration-300 h-full">
                  <CardContent className="p-6">
                    <div className="flex items-start gap-4">
                      <div className="bg-[#005B96] rounded-lg p-2 flex-shrink-0">
                        <MapPin className="text-white" size={20} />
                      </div>
                      <div>
                        <h3 className="font-bold text-[#005B96]">
                          {office.city}
                        </h3>
                        <p className="text-[#FF7F00] text-sm font-medium">
                          {office.country}
                        </p>
                        <p className="text-gray-600 text-sm mt-2">
                          {office.address}
                        </p>
                        <p className="text-gray-500 text-sm mt-1">
                          {office.phone}
                        </p>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              </AnimatedSection>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
