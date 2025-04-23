<script setup>

import { ref, onMounted } from 'vue';

const loading = ref(true);
const headerFooter = ref(null);

async function fetchData() {
  loading.value = true;

  const [headerFooterRes, headerFooterErr] = await getHeaderFooter();

  if (headerFooterRes) headerFooter.value = headerFooterRes;

  if (headerFooterErr) console.error("Error fetching header & footer:", headerFooterErr);

  loading.value = false;
}

onMounted(fetchData);
</script>
<template>
  <div>
    <article v-if="loading">
      <SpinLoader />
    </article>
    <article v-else>
      <PageNavbar :headerData="headerFooter" />

      <!-- ✅ Add the Terms Section -->
      <section class="bg-[#0D0D0D] py-16 px-6 md:px-16 text-white">
        <h2 class="text-3xl lg:text-5xl font-bold uppercase text-center block" style="margin:100px 0;">
          Terms & Conditions
        </h2>
        <div class="space-y-8 max-w-5xl mx-auto">
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
              1. Booking and Payment
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              To reserve a tour, please submit the Registration Form with a deposit as advised by our consultant. A written confirmation and more information about the tour will be sent to you.<br>
              Air-ticket: FULL payment of the air-fare + current applicable taxes and surcharges MUST be paid prior to issuance of the ticket.<br>
              This deposit is non-refundable once the ticketing and/or land arrangements are confirmed. The deposit payment forms part of your final payment. We will invoice you for the balance amount, which shall be payable at least 30 days prior to departure. Failure to comply with these conditions may result in an automatic cancellation of your reservation and forfeiture of your deposit.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
              2. Cancellations and Refund
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Notification of cancellation must be made in writing. Upon cancellation, the guest shall be liable to pay a fee to cover the estimate of cost and expenses incurred by X-Trekkers and/or its partners. This is expressed as a percentage of the total price and is calculated as indicated in Table B.<br>
              Table B<br>
              All administration fees, taxes, charges or levies imposed shall be borne by the guest.
            </p>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              For all hotel reservations, the guest will have to abide by the respective hotel’s terms and conditions.<br>
              * If non-refundable airfare & taxes form part of the whole package, the above cancellation charges will apply to the land arrangement cost. The entire cost of the non-refundable airfare & taxes will be payable by the guest in addition to the above charges incurred.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
              3. Amendment Charges
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Amendments to a booking can be made subject to availability of space. A compulsory administrative fee of S$ 50.00 will be levied for every amendment made to the original booking. No amendment can be made within 14 days prior to the date of departure. A change on the entire booking shall be considered as a cancellation and will be subject to cancellation charges as per clause 2 above.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
              4. Refund of Unused Services
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              No refund can be made in respect of accommodation, transportation, sightseeing tours, meals or any other services that are included in the tour price but not utilized or availed of by the guest.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
              5. Cancelled Tour
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              X-Trekkers reserves the right to cancel any trip prior to departure for any reason whatsoever, including insufficient sign-ups or logistical problems which will impede the operations. The refund of all payments received shall release X-Trekkers from any further liability. X-Trekkers is not responsible for non-recoverable expenses incurred by guests in preparing for a cancelled tour, such as non-refundable advance purchase of air tickets, visa fees, gears and equipment, etc., nor for any necessary additional arrangements should the applicant have embarked prior to the group’s departure date.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
              6. Late Payment
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Any late payment will entitle X-Trekkers to cancel the trip or amend the programme without prior notice.<br>
              X-Trekkers will be entitled to charge a late payment interest payment of 5% per annum.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
              7. Insurance
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              It is compulsory for all tour members to purchase travel insurance that covers medical evacuations, trip cancellations, hospitalization, medical claims, loss of baggage etc.
            </p>
          </div>
        </div>
      </section>

      <PageFooter :footerData="headerFooter" />
    </article>
  </div>
</template>
