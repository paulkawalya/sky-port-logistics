"use client";

import Link from "next/link";
import { ChevronRight, Send, MapPin, Phone, Mail, ArrowRight, Globe, Shield, Award } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

export default function Footer() {
  return (
    <footer className="relative bg-[#003D66] text-white overflow-hidden">
      {/* Decorative gradient overlay */}
      <div className="absolute inset-0 bg-gradient-to-b from-[#003D66] via-[#002B4D] to-[#001A33]" />
      <div className="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#FF7F00]/30 to-transparent" />
      <div className="absolute top-0 right-0 w-[500px] h-[500px] bg-[#FF7F00]/3 rounded-full blur-[150px]" />
      <div className="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#0078C8]/5 rounded-full blur-[120px]" />

      <div className="relative z-10">
        {/* Main Footer Content */}
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
            {/* Brand Column */}
            <div className="lg:col-span-4">
              <Link href="/" className="flex items-center gap-3 mb-6">
                <div className="relative">
                  <img src="/logo.png" alt="Sky Port Logistics" className="h-11 w-11 rounded-xl object-contain" />
                  <div className="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-[#FF7F00] rounded-full border-2 border-[#003D66]" />
                </div>
                <div>
                  <span className="text-xl font-extrabold tracking-tight text-white">
                    SKY<span className="text-[#FF7F00]">PORT</span>
                  </span>
                  <span className="block text-[9px] tracking-[0.35em] uppercase font-medium text-white/40">
                    Logistics
                  </span>
                </div>
              </Link>
              <p className="text-white/50 text-sm leading-relaxed mb-8 max-w-sm">
                Your trusted partner for global shipping and logistics solutions. Connecting businesses to the world with speed, security, and reliability since 2001.
              </p>
              <div className="space-y-3">
                <div className="flex items-center gap-3 text-white/50 text-sm">
                  <MapPin size={14} className="text-[#FF7F00] flex-shrink-0" />
                  <span>1200 Harbor Blvd, Los Angeles, CA</span>
                </div>
                <div className="flex items-center gap-3 text-white/50 text-sm">
                  <Phone size={14} className="text-[#FF7F00] flex-shrink-0" />
                  <span>+1 (800) 557-1234</span>
                </div>
                <div className="flex items-center gap-3 text-white/50 text-sm">
                  <Mail size={14} className="text-[#FF7F00] flex-shrink-0" />
                  <span>info@skyportlogistics.com</span>
                </div>
              </div>
            </div>

            {/* Quick Links */}
            <div className="lg:col-span-2">
              <h4 className="text-xs font-bold text-white/30 uppercase tracking-[0.2em] mb-5">Company</h4>
              <ul className="space-y-3">
                {[
                  { label: "About Us", href: "/about" },
                  { label: "Our Services", href: "/services" },
                  { label: "Industries", href: "/industries" },
                  { label: "Blog", href: "/blog" },
                  { label: "Careers", href: "/about#careers" },
                ].map((link) => (
                  <li key={link.label}>
                    <Link href={link.href} className="text-white/50 hover:text-[#FF7F00] transition-colors text-sm flex items-center gap-2 group">
                      <ChevronRight size={12} className="text-[#FF7F00]/0 group-hover:text-[#FF7F00] transition-all" />
                      {link.label}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>

            {/* Services */}
            <div className="lg:col-span-2">
              <h4 className="text-xs font-bold text-white/30 uppercase tracking-[0.2em] mb-5">Services</h4>
              <ul className="space-y-3">
                {[
                  { label: "Air Freight", href: "/services/air-freight" },
                  { label: "Ocean Freight", href: "/services/ocean-freight" },
                  { label: "Road Transport", href: "/services/road-transport" },
                  { label: "Warehousing", href: "/services/warehousing" },
                  { label: "Customs Clearance", href: "/services" },
                ].map((service) => (
                  <li key={service.label}>
                    <Link href={service.href} className="text-white/50 hover:text-[#FF7F00] transition-colors text-sm flex items-center gap-2 group">
                      <ChevronRight size={12} className="text-[#FF7F00]/0 group-hover:text-[#FF7F00] transition-all" />
                      {service.label}
                    </Link>
                  </li>
                ))}
              </ul>
            </div>

            {/* Newsletter */}
            <div className="lg:col-span-4">
              <h4 className="text-xs font-bold text-white/30 uppercase tracking-[0.2em] mb-5">Stay Updated</h4>
              <p className="text-white/50 text-sm mb-5 leading-relaxed">
                Subscribe to our newsletter for the latest logistics insights and industry updates.
              </p>
              <div className="flex gap-2 mb-6">
                <Input placeholder="Your email address" className="bg-white/5 border-white/10 text-white placeholder:text-white/30 h-11 rounded-xl focus:ring-[#FF7F00]/30" />
                <Button className="bg-[#FF7F00] hover:bg-[#CC6600] text-white rounded-xl px-4 h-11 btn-glow">
                  <Send size={16} />
                </Button>
              </div>
              <div className="space-y-3">
                <h4 className="text-xs font-bold text-white/30 uppercase tracking-[0.2em]">Certifications</h4>
                <div className="flex flex-wrap gap-2">
                  {["ISO 9001", "IATA", "FIATA", "AEO"].map((cert) => (
                    <span key={cert} className="bg-white/5 text-white/50 text-xs px-3 py-1.5 rounded-lg border border-white/5 font-medium">
                      {cert}
                    </span>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Bottom bar */}
        <div className="border-t border-white/5">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p className="text-white/30 text-sm">
              &copy; {new Date().getFullYear()} Sky Port Logistics. All rights reserved.
            </p>
            <div className="flex gap-8">
              {["Privacy Policy", "Terms of Service", "Cookie Policy"].map((link) => (
                <Link key={link} href="#" className="text-white/30 hover:text-[#FF7F00] transition-colors text-sm">
                  {link}
                </Link>
              ))}
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}
