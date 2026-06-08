"use client";

import { useState } from "react";
import Link from "next/link";
import {
  ArrowRight,
  Clock,
  Send,
  BookOpen,
  FileText,
  Newspaper,
  TrendingUp,
  Building2,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import AnimatedSection from "@/components/AnimatedSection";

const categories = [
  { id: "all", label: "All", icon: BookOpen },
  { id: "news", label: "Industry News", icon: Newspaper },
  { id: "case-studies", label: "Case Studies", icon: FileText },
  { id: "guides", label: "Guides", icon: TrendingUp },
  { id: "company", label: "Company Updates", icon: Building2 },
];

const blogPosts = [
  {
    id: 1,
    title: "How AI is Transforming Global Supply Chain Management",
    excerpt: "Artificial intelligence is reshaping how logistics companies predict demand, optimize routes, and manage inventory. Learn about the latest AI applications in supply chain management.",
    category: "news",
    date: "March 15, 2026",
    readTime: "8 min read",
    image: "/hero-bg.png",
  },
  {
    id: 2,
    title: "Case Study: Reducing Transit Time by 40% for a Pharma Client",
    excerpt: "Discover how Sky Port Logistics redesigned a pharmaceutical company's cold chain logistics to achieve a 40% reduction in transit time while maintaining GDP compliance.",
    category: "case-studies",
    date: "March 10, 2026",
    readTime: "12 min read",
    image: "/cargo-plane.png",
  },
  {
    id: 3,
    title: "The Complete Guide to International Shipping Documentation",
    excerpt: "Navigating international shipping documentation can be complex. This comprehensive guide covers bills of lading, commercial invoices, packing lists, and certificates of origin.",
    category: "guides",
    date: "March 5, 2026",
    readTime: "15 min read",
    image: "/warehouse.png",
  },
  {
    id: 4,
    title: "Sky Port Logistics Opens New Hub in Singapore",
    excerpt: "We're excited to announce the opening of our new regional distribution hub in Singapore, expanding our Asia-Pacific capabilities and enabling faster service across the region.",
    category: "company",
    date: "February 28, 2026",
    readTime: "4 min read",
    image: "/global-network.png",
  },
  {
    id: 5,
    title: "Sustainable Logistics: Reducing Your Carbon Footprint",
    excerpt: "As environmental regulations tighten, sustainable logistics practices are becoming essential. Explore strategies for reducing emissions across your supply chain.",
    category: "guides",
    date: "February 20, 2026",
    readTime: "10 min read",
    image: "/hero-ocean.png",
  },
  {
    id: 6,
    title: "Case Study: E-Commerce Fulfillment at Scale",
    excerpt: "Learn how we helped a fast-growing D2C brand scale from 1,000 to 50,000 daily orders while maintaining 99.5% accuracy and same-day dispatch.",
    category: "case-studies",
    date: "February 15, 2026",
    readTime: "11 min read",
    image: "/warehouse.png",
  },
  {
    id: 7,
    title: "2026 Ocean Freight Market Outlook: Rates, Capacity & Trends",
    excerpt: "Our analysis of the 2026 ocean freight market, including rate predictions, capacity forecasts, and emerging trends that will shape the industry.",
    category: "news",
    date: "February 10, 2026",
    readTime: "9 min read",
    image: "/cargo-ship.png",
  },
  {
    id: 8,
    title: "Sky Port Logistics Wins Global Logistics Excellence Award",
    excerpt: "We're proud to announce that Sky Port Logistics has been recognized with the 2026 Global Logistics Excellence Award for outstanding service and innovation.",
    category: "company",
    date: "February 5, 2026",
    readTime: "3 min read",
    image: "/hero-bg.png",
  },
];

export default function BlogPage() {
  const [activeCategory, setActiveCategory] = useState("all");

  const filteredPosts =
    activeCategory === "all"
      ? blogPosts
      : blogPosts.filter((post) => post.category === activeCategory);

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
              Insights
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">
              Logistics{" "}
              <span className="bg-gradient-to-r from-[#FF7F00] to-[#FFA040] bg-clip-text text-transparent">Insights</span>
            </h1>
            <p className="text-white/70 text-lg max-w-2xl mx-auto leading-relaxed">
              Stay informed with the latest industry news, expert guides, case
              studies, and company updates from the world of logistics.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Category Filter */}
      <section className="py-6 bg-white border-b border-gray-100 sticky top-20 z-30">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            {categories.map((cat) => {
              const CatIcon = cat.icon;
              return (
                <button
                  key={cat.id}
                  onClick={() => setActiveCategory(cat.id)}
                  className={`flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all whitespace-nowrap ${
                    activeCategory === cat.id
                      ? "bg-[#005B96] text-white shadow-md shadow-[#005B96]/20"
                      : "bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-100/80"
                  }`}
                >
                  <CatIcon size={16} />
                  {cat.label}
                </button>
              );
            })}
          </div>
        </div>
      </section>

      {/* Blog Grid */}
      <section className="py-28 bg-gray-50 relative">
        <div className="absolute inset-0 dot-pattern" />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {filteredPosts.length === 0 ? (
            <div className="text-center py-16">
              <p className="text-gray-400 text-lg">No posts found in this category.</p>
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {filteredPosts.map((post, idx) => (
                <AnimatedSection key={post.id} delay={idx * 0.05}>
                  <div className="group bg-white rounded-2xl shadow-sm hover:shadow-xl hover:shadow-[#005B96]/5 transition-all duration-500 h-full overflow-hidden cursor-pointer border border-gray-100/80 card-border-glow">
                    <div className="relative h-48 overflow-hidden">
                      <img
                        src={post.image}
                        alt={post.title}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-[#003D66]/40 to-transparent" />
                      <Badge className="absolute top-4 left-4 bg-[#FF7F00] text-white border-0 text-xs shadow-md shadow-[#FF7F00]/20 rounded-lg">
                        {categories.find((c) => c.id === post.category)?.label}
                      </Badge>
                    </div>
                    <div className="p-6">
                      <div className="flex items-center gap-3 text-xs text-gray-400 mb-3">
                        <span>{post.date}</span>
                        <span className="flex items-center gap-1">
                          <Clock size={12} />
                          {post.readTime}
                        </span>
                      </div>
                      <h3 className="font-bold text-[#003D66] mb-2 leading-snug group-hover:text-[#005B96] transition-colors line-clamp-2">
                        {post.title}
                      </h3>
                      <p className="text-gray-500 text-sm leading-relaxed line-clamp-3">
                        {post.excerpt}
                      </p>
                      <span className="inline-flex items-center gap-1 text-[#FF7F00] text-sm font-semibold mt-4 group-hover:gap-2 transition-all">
                        Read More <ArrowRight size={14} />
                      </span>
                    </div>
                  </div>
                </AnimatedSection>
              ))}
            </div>
          )}
        </div>
      </section>

      {/* Newsletter */}
      <section className="py-28 bg-white relative">
        <div className="absolute inset-0 diagonal-stripes" />
        <div className="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection>
            <div className="relative overflow-hidden rounded-2xl">
              <div className="absolute inset-0 bg-gradient-to-br from-[#003D66] via-[#005B96] to-[#003D66]" />
              <div className="absolute inset-0 grid-pattern opacity-10" />
              <div className="relative z-10 p-8 sm:p-12 text-center">
                <div className="w-14 h-14 rounded-xl bg-[#FF7F00]/15 flex items-center justify-center mx-auto mb-6 border border-[#FF7F00]/20">
                  <Send className="text-[#FF7F00]" size={28} />
                </div>
                <h2 className="text-3xl sm:text-4xl font-extrabold text-white mb-3 tracking-tight">
                  Subscribe to Our Newsletter
                </h2>
                <p className="text-white/70 mb-8 max-w-lg mx-auto leading-relaxed">
                  Get the latest logistics insights, industry news, and expert
                  guides delivered to your inbox every week.
                </p>
                <div className="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                  <Input
                    placeholder="Enter your email address"
                    className="flex-1 h-12 bg-white/8 border-white/15 text-white placeholder:text-white/30 rounded-xl px-6 backdrop-blur-sm focus:ring-[#FF7F00]/50"
                  />
                  <Button className="h-12 bg-[#FF7F00] hover:bg-[#CC6600] text-white font-bold px-6 rounded-xl shadow-lg shadow-[#FF7F00]/25 btn-glow">
                    Subscribe
                  </Button>
                </div>
              </div>
            </div>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
