import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";
import { Toaster } from "@/components/ui/toaster";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Sky Port Logistics - Global Shipping & Freight Solutions",
  description:
    "Sky Port Logistics delivers world-class air freight, ocean freight, road transport, and warehousing solutions. Connecting businesses across the globe with speed and reliability.",
  keywords: [
    "Sky Port Logistics",
    "shipping",
    "freight",
    "logistics",
    "air freight",
    "ocean freight",
    "cargo",
    "supply chain",
    "warehousing",
    "global shipping",
  ],
  authors: [{ name: "Sky Port Logistics" }],
  icons: {
    icon: "/logo.jpeg",
  },
  openGraph: {
    title: "Sky Port Logistics - Global Shipping & Freight Solutions",
    description:
      "World-class air freight, ocean freight, road transport, and warehousing solutions.",
    siteName: "Sky Port Logistics",
    type: "website",
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" suppressHydrationWarning>
      <body
        className={`${geistSans.variable} ${geistMono.variable} antialiased bg-background text-foreground`}
      >
        {children}
        <Toaster />
      </body>
    </html>
  );
}
