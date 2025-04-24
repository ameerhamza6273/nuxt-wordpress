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
      <section class="bg-[#0D0D0D] py-12  text-white">
        <h2 class="bg-[#000] text-2xl lg:text-4xl font-bold uppercase text-center block"
          style="padding:100px 0;margin:50px 0 ;">
          Terms & Conditions
        </h2>
        <div class=" px-6 md:px-24">
          <p class="text-white text-base whitespace-pre-line leading-relaxed">
            X-Trekkers refers to X-Trekkers Adventure Consultant Pte Ltd
          </p>

          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
              1. Booking and Payment
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              To reserve a tour, please submit the Registration Form with a deposit as advised by our consultant. A
              written confirmation and more information about the tour will be sent to you.<br>
              Air-ticket: FULL payment of the air-fare + current applicable taxes and surcharges MUST be paid prior to
              issuance of the ticket.<br>
              This deposit is non-refundable once the ticketing and/or land arrangements are confirmed. The deposit
              payment forms part of your final payment. We will invoice you for the balance amount, which shall be
              payable at least 30 days prior to departure. Failure to comply with these conditions may result in an
              automatic cancellation of your reservation and forfeiture of your deposit.
            </p>
          </div>

          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              2. Cancellations and Refund
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Notification of cancellation must be made in writing. Upon cancellation, the guest shall be liable to pay
              a fee to cover the estimate of cost and expenses incurred by X-Trekkers and/or its partners. This is
              expressed as a percentage of the total price and is calculated as indicated in Table B.<br>
              Table B<br>
              All administration fees, taxes, charges or levies imposed shall be borne by the guest.
            </p>
            <div class="max-w-[320px] mx-auto border p-2 my-4">
              <table class="w-full border-collapse text-white font-sans">
                <tbody>
                  <tr>
                    <td class="p-3 border border-white/30 ">Period of notice</td>
                    <td class="p-3 border border-white/30 ">Cancellation Fee</td>
                  </tr>
                  <tr>
                    <td class="p-3 border border-white/30 ">31-45 days prior to departure</td>
                    <td class="p-3 border border-white/30 ">50% of total price</td>
                  </tr>
                  <tr>
                    <td class="p-3 border border-white/30 ">15-30 days prior to departure</td>
                    <td class="p-3 border border-white/30 ">75% of total price</td>
                  </tr>
                  <tr>
                    <td class="p-3 border border-white/30 ">1-14 days prior to departure</td>
                    <td class="p-3 border border-white/30 ">100% of total price</td>
                  </tr>
                </tbody>
              </table>
            </div>


            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              For all hotel reservations, the guest will have to abide by the respective hotel’s terms and
              conditions.<br><br>
              * If non-refundable airfare & taxes form part of the whole package, the above cancellation charges will
              apply to the land arrangement cost. The entire cost of the non-refundable airfare & taxes will be payable
              by the guest in addition to the above charges incurred.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              3. Amendment Charges
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Amendments to a booking can be made subject to availability of space. A compulsory administrative fee of
              S$ 50.00 will be levied for every amendment made to the original booking. No amendment can be made within
              14 days prior to the date of departure. A change on the entire booking shall be considered as a
              cancellation and will be subject to cancellation charges as per clause 2 above.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              4. Refund of Unused Services
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              No refund can be made in respect of accommodation, transportation, sightseeing tours, meals or any other
              services that are included in the tour price but not utilized or availed of by the guest.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              5. Cancelled Tour
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              X-Trekkers reserves the right to cancel any trip prior to departure for any reason whatsoever, including
              insufficient sign-ups or logistical problems which will impede the operations. The refund of all payments
              received shall release X-Trekkers from any further liability. X-Trekkers is not responsible for
              non-recoverable expenses incurred by guests in preparing for a cancelled tour, such as non-refundable
              advance purchase of air tickets, visa fees, gears and equipment, etc., nor for any necessary additional
              arrangements should the applicant have embarked prior to the group’s departure date.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              6. Late Payment
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Any late payment will entitle X-Trekkers to cancel the trip or amend the programme without prior
              notice.<br>
              X-Trekkers will be entitled to charge a late payment interest payment of 5% per annum.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              7. Insurance
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              It is compulsory for all tour members to purchase travel insurance that covers medical evacuations, trip
              cancellations, hospitalization, medical claims, loss of baggage etc.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              8. Right to Reject
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              It is vital that persons with medical problems make them known to us well in advance of their trip
              departure. X-Trekkers reserves the right to cancel or withdraw any itinerary or any booking made for a
              client or decline to accept or retain any person as a member of a trip, if any such person appears likely
              to endanger the health, safety or impair the comfort and enjoyment of others. X-Trekkers further reserves
              the right to cancel the reservation of a particular member or members, if for any reason, any carrier,
              hotel or other contractor refuses to allow the particular member or members to participate in the tour. In
              any of the foregoing events, no refunds shall be given to such member or members.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              9. Travel Documents
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              It is your responsibility to ensure that your travel document and other necessary travel related documents
              are in order. X-Trekkers will advise you on what documentation is required. As a guide, you must have an
              International Passport or other recognized travel document that should be valid for at least six months
              from the date of travel. You must have the necessary visas, vaccinations and health certificates as
              required for your travel destination. X-Trekkers will, wherever possible, assist you to obtain the
              necessary visas. Service charges and visa fee levied will be charged to the traveller’s account.
              X-Trekkers cannot however guarantee the approval of your application. A full refund of all monies paid to
              X-Trekkers will be made if the result of the rejection is notified to X-Trekkers at least 45 days prior to
              departure date. If less than 45 days notice is given, the relevant cancellation charges will apply.
              X-Trekkers cannot be held responsible or liable for any expenses, reimbursement or refund of tour prices
              if any passenger is refused entry by any country on the tour for whatsoever reasons, including lack of the
              necessary documents.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              10. Possible Changes
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Airline schedules and local conditions may require sightseeing tours, accommodations, hotels and travel
              routines to be changed. Should this occur, X-Trekkers will, whenever possible, use its best endeavors to
              make alternative arrangements.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              11. Tour Information & Prices
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Price shown is current at the time of printing. These prices are subject to change due to increases in
              airfares, taxes or surcharges; other transport costs; hotel rates, government taxes, etc., and therefore
              X-Trekkers reserves the right to increase the price accordingly without notice.
            </p>
          </div>
          <div>
            <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
              12. Responsibility
            </h3>
            <p class="text-white text-base whitespace-pre-line leading-relaxed">
              Please read carefully. These paragraphs define our responsibility with respect to all of our tours.
              Payment of deposit represents an acceptance by the member of the following terms and conditions:<br>
              X-Trekkers gives notice that services provided in connection with the tours listed are rendered by
              suppliers who are not agents or employees of X-Trekkers. All arrangements for accommodations, meals,
              tours, transportation and all other related services have been made by X-Trekkers only as an agent for the
              individual tour member. While we endeavor to make tour arrangements with reputable suppliers, each tour
              member agrees that X-Trekkers shall not be liable for any delay, mishap, inconvenience, expense,
              irregularity, bodily injury or death to a person or persons, or damage to property occasioned through the
              negligence or default of any company or individual engaged in providing these arranged services.<br>
              X-Trekkers shall not be liable, directly or indirectly, for bodily injury or damage to property for any
              reason whatsoever, including, but not limited to the following causes: Acts of God, detention, annoyance,
              weather, strikes, civil unrest, theft, default, and change in government regulations, terrorism,
              negligence, war or failure of conveyance to arrive or depart as scheduled.<br>
              X-Trekkers reserves the right to substitute hotels and tour leaders and to alter the itinerary when deemed
              necessary or necessary.<br>
              All claims against X-Trekkers must be made in writing within 14 days after the completion of the tour. All
              the terms and conditions mentioned above cannot be changed except in writing by X-Trekkers.<br>
              Please note that these terms and conditions are correct at the time of publishing. X-Trekkers Adventure
              Consultant Pte Ltd reserves the right to change/amend any of these booking terms and conditions.<br>
            </p>
          </div>
        </div>
      </section>

      <PageFooter :footerData="headerFooter" />
    </article>
  </div>
</template>
