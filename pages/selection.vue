<template>
    <div>
        <PageNavbar />

        <main class="bg-white">
            <TopBar />

            <section
                class="relative py-16 md:py-24 text-center text-white border-b border-gray-800 bg-black overflow-hidden">
                <div class="absolute inset-0 z-0">
                    <NuxtImg src="/selection-main.png" class="w-full h-full object-cover opacity-40" />
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>

                <div class="container mx-auto px-4 relative z-10">
                    <span class="text-[#e31e24] font-black text-sm tracking-[4px] uppercase block mb-4">
                        Support Center
                    </span>
                    <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tighter uppercase">
                        Returns & Exchanges
                    </h1>
                    <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                        Need to return a part? Choose your return type below to start the process.
                        Our team is here to ensure a smooth transition.
                    </p>
                </div>
            </section>
            <section class="py-16 relative z-20">
                <div class="container max-w-[1200px] mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div
                            class="bg-white p-8 rounded-2xl shadow-2xl border border-gray-50 hover:-translate-y-2 transition-all duration-300">
                            <div
                                class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center mb-6 text-[#e31e24]">
                                <i class="fa-solid fa-box-open text-2xl"></i>
                            </div>
                            <h3 class="font-black text-xl text-gray-900 mb-3 uppercase tracking-tight">New & Unused</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                                Returned in original manufacturer's packaging within 90 days of purchase.
                            </p>
                            <button
                                class="w-full py-3 bg-[#1a1a1a] text-white font-bold rounded-lg hover:bg-[#e31e24] transition-colors uppercase text-xs tracking-widest">
                                Return New Part
                            </button>
                        </div>

                        <div
                            class="bg-white p-8 rounded-2xl shadow-2xl border-2 border-[#f2a900] hover:-translate-y-2 transition-all duration-300">
                            <div
                                class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center mb-6 text-[#f2a900]">
                                <i class="fa-solid fa-recycle text-2xl"></i>
                            </div>
                            <h3 class="font-black text-xl text-gray-900 mb-3 uppercase tracking-tight">Submit Core</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                                Returning a part with a core charge? Get your credit back by returning it in original
                                packaging.
                            </p>
                            <button
                                class="w-full py-3 bg-[#f2a900] text-white font-bold rounded-lg hover:bg-black transition-colors uppercase text-xs tracking-widest">
                                Submit Core Return
                            </button>
                        </div>

                        <div
                            class="bg-white p-8 rounded-2xl shadow-2xl border border-gray-50 hover:-translate-y-2 transition-all duration-300">
                            <div
                                class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center mb-6 text-[#e31e24]">
                                <i class="fa-solid fa-shield-halved text-2xl"></i>
                            </div>
                            <h3 class="font-black text-xl text-gray-900 mb-3 uppercase tracking-tight">Lifetime
                                Replacement</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                                Does not require original box. Place a new order first before submitting your guarantee
                                claim.
                            </p>
                            <button
                                class="w-full py-3 bg-[#e31e24] text-white font-bold rounded-lg hover:bg-black transition-colors uppercase text-xs tracking-widest">
                                Start Claim
                            </button>
                        </div>

                    </div>
                </div>
            </section>

            <section class="py-20 bg-gray-50">
                <div class="container max-w-[1200px] mx-auto px-4">
                    <div class="flex flex-col lg:flex-row gap-16">

                        <div class="lg:w-1/2">
                            <h2
                                class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-8 border-l-4 border-[#e31e24] pl-6">
                                Return Guidelines
                            </h2>
                            <div class="space-y-6">
                                <div v-for="(rule, index) in returnRules" :key="index" class="flex gap-4">
                                    <i class="fa-solid fa-circle-check text-[#e31e24] mt-1"></i>
                                    <p class="text-gray-600 text-sm"><span class="font-bold text-gray-900 uppercase">{{
                                        rule.label }}:</span> {{ rule.text }}</p>
                                </div>
                            </div>

                            <div class="mt-10 p-6 bg-red-50 border border-red-100 rounded-xl">
                                <h5 class="text-red-700 font-bold flex items-center gap-2 mb-2 uppercase text-sm">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Non-Sellable Policy
                                </h5>
                                <p class="text-red-600/80 text-xs leading-relaxed">
                                    Items that have been installed, excessively handled, or missing the manufacturer’s
                                    packing are ineligible for return. A 15% restocking fee applies to items without a
                                    printed returns form.
                                </p>
                            </div>
                        </div>

                        <div class="lg:w-1/2 space-y-6">
                            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                                <h4 class="font-bold uppercase tracking-tight mb-4 flex items-center gap-3">
                                    <i class="fa-solid fa-flask text-[#e31e24]"></i> Returning Fluids
                                </h4>
                                <p class="text-gray-500 text-sm italic">
                                    Fluids must be securely contained in original containers, double-bagged in sealed
                                    plastic, and placed in a sturdy corrugated box. Leaking or misrepresented fluids
                                    will be discarded.
                                </p>
                            </div>
                            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                                <h4 class="font-bold uppercase tracking-tight mb-4 flex items-center gap-3">
                                    <i class="fa-solid fa-receipt text-[#e31e24]"></i> Sales Tax Refunds
                                </h4>
                                <p class="text-gray-500 text-sm">
                                    If you paid sales tax on your original order, the tax amount will be automatically
                                    included in your refund total upon successful processing.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="py-24 bg-white">
                <div class="container max-w-[900px] mx-auto px-4">
                    <div class="text-center mb-16">
                        <span class="text-[#e31e24] font-black text-[11px] tracking-[4px] uppercase block mb-3">Got
                            Questions?</span>
                        <h2 class="text-3xl md:text-5xl font-black text-gray-900 uppercase tracking-tighter">Returns FAQ
                        </h2>
                        <div class="w-16 h-1 bg-[#f2a900] mx-auto mt-6 rounded-full"></div>
                    </div>

                    <div class="space-y-4">
                        <div v-for="(faq, index) in faqs" :key="index"
                            class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                            <button @click="toggleFaq(index)"
                                class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                                <span class="font-bold text-gray-900 uppercase tracking-tight text-sm md:text-base">
                                    {{ faq.question }}
                                </span>
                                <i
                                    :class="['fa-solid fa-chevron-down text-[#e31e24] transition-transform duration-300', activeIndex === index ? 'rotate-180' : '']"></i>
                            </button>

                            <div v-show="activeIndex === index"
                                class="p-6 bg-gray-50 border-t border-gray-100 text-gray-600 text-sm leading-relaxed italic">
                                {{ faq.answer }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 text-center p-8 bg-[#212529] rounded-2xl shadow-xl">
                        <p class="text-gray-400 text-sm mb-4">Still have questions about your specific part?</p>
                        <a href="mailto:support@logicautoparts.com"
                            class="text-white font-black uppercase tracking-widest text-xs border-b border-[#e31e24] pb-1 hover:text-[#e31e24] transition-colors">
                            Contact Support Team
                        </a>
                    </div>
                </div>
            </section>
        </main>
        <PageFooter />
    </div>
</template>

<script setup>
import { ref } from 'vue';

const activeIndex = ref(0); // Pehla FAQ open rahega

const toggleFaq = (index) => {
    activeIndex.value = activeIndex.value === index ? null : index;
};

const faqs = [
    {
        question: "What parts does the 90-day return policy apply to?",
        answer: "This applies to all new, unused, and uninstalled products in their original manufacturer's packaging. After 90 days, we cannot issue credits or refunds for returned merchandise."
    },
    {
        question: "What if I received a defective part?",
        answer: "Defective items must be reported within 90 days. Please note that items damaged during installation or used for diagnostic purposes are not eligible for a defective return."
    },
    {
        question: "Will my Sales Tax be refunded?",
        answer: "Yes. If your state required sales tax on the original purchase, that amount will be fully refunded along with the product cost once the return is processed."
    },
    {
        question: "Are there any 'One-Time Use' item restrictions?",
        answer: "Yes. Consumable items like additives, cleaners, and shop supplies intended for single use are ineligible for Lifetime Guarantee or defective returns once opened."
    },
    {
        question: "Why was my return denied or charged a restocking fee?",
        answer: "Returns are typically denied if the part shows signs of installation, or if the manufacturer's box is damaged/marked. A 15% restocking fee is applied if the physical Returns Form is not included in your shipment."
    }
];
const returnRules = [
    { label: 'Returns Form', text: 'Must be printed and included physically with every return to avoid restocking fees.' },
    { label: 'Shipping Fees', text: 'Customers are responsible for return shipping costs and carrier insurance.' },
    { label: 'Original Box', text: 'New parts must be returned in the original manufacturer’s packaging without markings.' },
    { label: 'Time Frame', text: 'Refunds are only issued for parts received within 90 days of the original purchase.' }
];
</script>