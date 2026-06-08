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
              Insights
            </span>
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
              Logistics <span className="text-[#FF7F00]">Insights</span>
            </h1>
            <p className="text-white/80 text-lg max-w-2xl mx-auto">
              Stay informed with the latest industry news, expert guides, case
              studies, and company updates from the world of logistics.
            </p>
          </AnimatedSection>
        </div>
      </section>

      {/* Category Filter */}
      <section className="py-8 bg-white border-b sticky top-20 z-30">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            {categories.map((cat) => {
              const CatIcon = cat.icon;
              return (
                <button
                  key={cat.id}
                  onClick={() => setActiveCategory(cat.id)}
                  className={`flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap ${
                    activeCategory === cat.id
                      ? "bg-[#005B96] text-white shadow-md"
                      : "bg-gray-100 text-gray-600 hover:bg-gray-200"
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
      <section className="py-16 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {filteredPosts.length === 0 ? (
            <div className="text-center py-16">
              <p className="text-gray-500 text-lg">No posts found in this category.</p>
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {filteredPosts.map((post, idx) => (
                <AnimatedSection key={post.id} delay={idx * 0.05}>
                  <Card className="group border-0 shadow-lg hover:shadow-2xl transition-all duration-500 h-full overflow-hidden cursor-pointer">
                    <div className="relative h-48 overflow-hidden">
                      <img
                        src={post.image}
                        alt={post.title}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
                      <Badge className="absolute top-4 left-4 bg-[#FF7F00] text-white border-0 text-xs">
                        {categories.find((c) => c.id === post.category)?.label}
                      </Badge>
                    </div>
                    <CardContent className="p-5">
                      <div className="flex items-center gap-3 text-xs text-gray-500 mb-3">
                        <span>{post.date}</span>
                        <span className="flex items-center gap-1">
                          <Clock size={12} />
                          {post.readTime}
                        </span>
                      </div>
                      <h3 className="font-bold text-[#005B96] mb-2 leading-snug group-hover:text-[#0078C8] transition-colors line-clamp-2">
                        {post.title}
                      </h3>
                      <p className="text-gray-600 text-sm leading-relaxed line-clamp-3">
                        {post.excerpt}
                      </p>
                      <span className="inline-flex items-center gap-1 text-[#FF7F00] text-sm font-semibold mt-4 group-hover:gap-2 transition-all">
                        Read More <ArrowRight size={14} />
                      </span>
                    </CardContent>
                  </Card>
                </AnimatedSection>
              ))}
            </div>
          )}
        </div>
      </section>

      {/* Newsletter */}
      <section className="py-20 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <AnimatedSection>
            <Card className="border-0 shadow-xl overflow-hidden">
              <CardContent className="p-0">
                <div className="bg-gradient-to-r from-[#005B96] to-[#003D66] p-8 sm:p-12 text-center">
                  <Send className="text-[#FF7F00] mx-auto mb-4" size={40} />
                  <h2 className="text-2xl sm:text-3xl font-bold text-white mb-3">
                    Subscribe to Our Newsletter
                  </h2>
                  <p className="text-white/80 mb-6 max-w-lg mx-auto">
                    Get the latest logistics insights, industry news, and expert
                    guides delivered to your inbox every week.
                  </p>
                  <div className="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <Input
                      placeholder="Enter your email address"
                      className="flex-1 h-12 bg-white/10 border-white/20 text-white placeholder:text-white/50 rounded-full px-6"
                    />
                    <Button className="h-12 bg-[#FF7F00] hover:bg-[#CC6600] text-white font-semibold px-6 rounded-full">
                      Subscribe
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          </AnimatedSection>
        </div>
      </section>
    </div>
  );
}
