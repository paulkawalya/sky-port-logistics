"use client";

import Link from "next/link";
import { ChevronRight, Send, MapPin, Phone, Mail } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

export default function Footer() {
  return (
    <footer className="bg-[#003D66] text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
          {/* Brand */}
          <div>
            <Link href="/" className="flex items-center gap-3 mb-6">
              <img
                src="/logo.jpeg"
                alt="Sky Port Logistics"
                className="h-10 w-10 rounded-full object-cover ring-2 ring-[#FF7F00]"
              />
              <div>
                <span className="text-lg font-bold tracking-tight text-white">
                  SKY PORT
                </span>
                <span className="block text-[10px] tracking-[0.3em] uppercase text-[#FF7F00]">
                  Logistics
                </span>
              </div>
            </Link>
            <p className="text-white/60 text-sm leading-relaxed mb-6">
              Your trusted partner for global shipping and logistics solutions.
              Connecting businesses to the world with speed, security, and
              reliability since 2001.
            </p>
            <div className="space-y-3">
              <div className="flex items-center gap-3 text-white/60 text-sm">
                <MapPin size={16} className="text-[#FF7F00] flex-shrink-0" />
                <span>1200 Harbor Blvd, Los Angeles, CA</span>
              </div>
              <div className="flex items-center gap-3 text-white/60 text-sm">
                <Phone size={16} className="text-[#FF7F00] flex-shrink-0" />
                <span>+1 (800) 557-1234</span>
              </div>
              <div className="flex items-center gap-3 text-white/60 text-sm">
                <Mail size={16} className="text-[#FF7F00] flex-shrink-0" />
                <span>info@skyportlogistics.com</span>
              </div>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="font-bold text-white mb-4">Quick Links</h4>
            <ul className="space-y-3">
              {[
                { label: "About Us", href: "/about" },
                { label: "Our Services", href: "/services" },
                { label: "Track Shipment", href: "/tracking" },
                { label: "Industries", href: "/industries" },
                { label: "Blog", href: "/blog" },
                { label: "Contact", href: "/contact" },
              ].map((link) => (
                <li key={link.label}>
                  <Link
                    href={link.href}
                    className="text-white/60 hover:text-[#FF7F00] transition-colors text-sm flex items-center gap-2"
                  >
                    <ChevronRight size={14} />
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Services */}
          <div>
            <h4 className="font-bold text-white mb-4">Our Services</h4>
            <ul className="space-y-3">
              {[
                { label: "Air Freight", href: "/services/air-freight" },
                { label: "Ocean Freight", href: "/services/ocean-freight" },
                { label: "Road Transport", href: "/services/road-transport" },
                { label: "Warehousing", href: "/services/warehousing" },
                { label: "Customs Clearance", href: "/services" },
                { label: "Supply Chain Consulting", href: "/services" },
              ].map((service) => (
                <li key={service.label}>
                  <Link
                    href={service.href}
                    className="text-white/60 hover:text-[#FF7F00] transition-colors text-sm flex items-center gap-2"
                  >
                    <ChevronRight size={14} />
                    {service.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Newsletter */}
          <div>
            <h4 className="font-bold text-white mb-4">Stay Updated</h4>
            <p className="text-white/60 text-sm mb-4">
              Subscribe to our newsletter for the latest logistics insights and
              industry updates.
            </p>
            <div className="flex gap-2">
              <Input
                placeholder="Your email"
                className="bg-white/10 border-white/20 text-white placeholder:text-white/50 h-10 rounded-full"
              />
              <Button className="bg-[#FF7F00] hover:bg-[#CC6600] text-white rounded-full px-4 h-10">
                <Send size={16} />
              </Button>
            </div>
            <div className="mt-6">
              <h4 className="font-bold text-white mb-3">Certifications</h4>
              <div className="flex flex-wrap gap-2">
                {["ISO 9001", "IATA", "FIATA", "AEO"].map((cert) => (
                  <span
                    key={cert}
                    className="bg-white/10 text-white/70 text-xs px-3 py-1 rounded-full"
                  >
                    {cert}
                  </span>
                ))}
              </div>
            </div>
          </div>
        </div>

        <div className="border-t border-white/10 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p className="text-white/40 text-sm">
            &copy; {new Date().getFullYear()} Sky Port Logistics. All rights
            reserved.
          </p>
          <div className="flex gap-6">
            {["Privacy Policy", "Terms of Service", "Cookie Policy"].map(
              (link) => (
                <Link
                  key={link}
                  href="#"
                  className="text-white/40 hover:text-[#FF7F00] transition-colors text-sm"
                >
                  {link}
                </Link>
              )
            )}
          </div>
        </div>
      </div>
    </footer>
  );
}
