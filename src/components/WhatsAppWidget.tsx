"use client";

import { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { X, Mail, Phone, MessageCircle } from "lucide-react";

export default function WhatsAppWidget() {
  const [isOpen, setIsOpen] = useState(false);

  const phoneNumber = "256782843531";
  const emailAddress = "info@skyportlogisticsltd.com";
  const serviceDescription =
    "Air Freight, Ocean Freight, Road Transport, Warehousing, Customs Brokerage & End-to-End Supply Chain Solutions.";

  const whatsappLink = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(
    "Hello Sky Port Logistics! I'm interested in your shipping and logistics services. Please assist me."
  )}`;

  return (
    <div className="fixed bottom-6 right-6 z-[999] flex flex-col items-end gap-3">
      {/* Popup Card */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0, y: 20, scale: 0.9 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 20, scale: 0.9 }}
            transition={{ duration: 0.3, ease: [0.25, 0.46, 0.45, 0.94] }}
            className="w-80 sm:w-96 bg-white rounded-2xl shadow-2xl shadow-[#003D66]/20 border border-gray-100 overflow-hidden"
          >
            {/* Header */}
            <div className="relative bg-gradient-to-r from-[#25D366] to-[#128C7E] px-5 py-4">
              <button
                onClick={() => setIsOpen(false)}
                className="absolute top-3 right-3 w-7 h-7 flex items-center justify-center rounded-full bg-white/15 hover:bg-white/25 transition-colors"
                aria-label="Close"
              >
                <X size={14} className="text-white" />
              </button>
              <div className="flex items-center gap-3">
                <div className="w-11 h-11 bg-white rounded-full flex items-center justify-center shadow-md">
                  <MessageCircle size={22} className="text-[#25D366]" />
                </div>
                <div>
                  <h4 className="text-white font-bold text-sm">Sky Port Logistics</h4>
                  <p className="text-white/70 text-xs">Typically replies within minutes</p>
                </div>
              </div>
            </div>

            {/* Body */}
            <div className="p-5 space-y-4">
              {/* Service description bubble */}
              <div className="bg-[#DCF8C6]/60 rounded-xl rounded-tl-none p-4 relative">
                <div className="absolute -top-1 left-0 w-2 h-2 bg-[#DCF8C6]/60" />
                <p className="text-[#003D66] text-sm leading-relaxed">
                  {serviceDescription}
                </p>
                <p className="text-[#003D66]/40 text-[10px] mt-2 text-right">
                  Sky Port Logistics
                </p>
              </div>

              {/* Contact details */}
              <div className="space-y-2.5">
                <a
                  href={`tel:+${phoneNumber}`}
                  className="flex items-center gap-3 bg-gray-50 hover:bg-[#25D366]/5 rounded-xl p-3 transition-colors group"
                >
                  <div className="w-9 h-9 bg-[#25D366]/10 rounded-lg flex items-center justify-center group-hover:bg-[#25D366]/20 transition-colors">
                    <Phone size={16} className="text-[#25D366]" />
                  </div>
                  <div>
                    <p className="text-xs text-gray-400 font-medium">Call / WhatsApp</p>
                    <p className="text-sm font-semibold text-[#003D66]">+256 782 843531</p>
                  </div>
                </a>

                <a
                  href={`mailto:${emailAddress}`}
                  className="flex items-center gap-3 bg-gray-50 hover:bg-[#005B96]/5 rounded-xl p-3 transition-colors group"
                >
                  <div className="w-9 h-9 bg-[#005B96]/10 rounded-lg flex items-center justify-center group-hover:bg-[#005B96]/20 transition-colors">
                    <Mail size={16} className="text-[#005B96]" />
                  </div>
                  <div>
                    <p className="text-xs text-gray-400 font-medium">Email</p>
                    <p className="text-sm font-semibold text-[#003D66]">{emailAddress}</p>
                  </div>
                </a>
              </div>

              {/* CTA Button */}
              <a
                href={whatsappLink}
                target="_blank"
                rel="noopener noreferrer"
                className="flex items-center justify-center gap-2 w-full bg-[#25D366] hover:bg-[#128C7E] text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-[#25D366]/25 hover:shadow-[#25D366]/40 text-sm"
              >
                <MessageCircle size={18} />
                Start WhatsApp Chat
              </a>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Floating Button */}
      <motion.button
        onClick={() => setIsOpen(!isOpen)}
        className="relative w-16 h-16 bg-[#25D366] hover:bg-[#128C7E] rounded-full flex items-center justify-center shadow-xl shadow-[#25D366]/30 hover:shadow-[#25D366]/50 transition-all group"
        whileHover={{ scale: 1.1 }}
        whileTap={{ scale: 0.95 }}
        aria-label="Open WhatsApp chat"
      >
        <AnimatePresence mode="wait">
          {isOpen ? (
            <motion.div
              key="close"
              initial={{ rotate: -90, opacity: 0 }}
              animate={{ rotate: 0, opacity: 1 }}
              exit={{ rotate: 90, opacity: 0 }}
              transition={{ duration: 0.2 }}
            >
              <X size={26} className="text-white" />
            </motion.div>
          ) : (
            <motion.div
              key="whatsapp"
              initial={{ rotate: 90, opacity: 0 }}
              animate={{ rotate: 0, opacity: 1 }}
              exit={{ rotate: -90, opacity: 0 }}
              transition={{ duration: 0.2 }}
            >
              <svg
                width="30"
                height="30"
                viewBox="0 0 24 24"
                fill="white"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
              </svg>
            </motion.div>
          )}
        </AnimatePresence>

        {/* Pulse ring when closed */}
        {!isOpen && (
          <span className="absolute inset-0 rounded-full animate-ping bg-[#25D366]/30 pointer-events-none" />
        )}
      </motion.button>
    </div>
  );
}
