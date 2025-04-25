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
                    Privacy Policy
                </h2>
                <div class=" px-6 md:px-24">

                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-2">
                            1. Background
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            This Privacy Notice (“Notice”) sets out how X-Trekkers Adventure Consultant Pte Ltd
                            (“X-Trekkers”) protects the privacy of your personal information.<br>
                            X-Trekkers is one of Singapore’s most established adventure travel agency. We need to
                            collect, use and disclose personal information in order to perform our business functions
                            and activities, including making and managing travel bookings on behalf of our customers. We
                            are firmly committed to protecting the privacy and confidentiality of personal information
                            and to maintaining various physical, electronic and procedural safeguards to protect
                            personal information in our care.<br><br>
                            For the purposes of the General Data Protection Regulation 2016/679 (“GDPR”) we will be
                            either the “data processor” or “data controller” for any personal information you provide to
                            us in connection with our relationship. The precise role we will be assuming is dependent on
                            the context of our relationship with you and the purpose of the processing being
                            undertaken.<br><br>
                            By providing personal information to us, you agree that this Notice will apply to how we
                            handle your personal information and you consent to us collecting, using and disclosing your
                            personal information as detailed in this Notice. If you do not agree with any part of this
                            Notice, you must not provide your personal information to us. If you do not provide us with
                            your personal information, or if you withdraw a consent that you have given under this
                            Notice, this may affect our ability to provide services to you or negatively impact the
                            services we can provide to you. For example, most travel bookings must be made under the
                            traveller’s full name and must include contact details and appropriate identification (e.g.
                            passport details). We cannot make bookings for you without that information. There may be
                            instances where your local data protection laws impose more restrictive information handling
                            practices than the practices set out in this Notice. Where this occurs we will adjust our
                            information handling practices in your jurisdiction to comply with these local data
                            protection laws.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            2. What personal information do we collect?
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            Personal information has the meaning given under your local data protection law, and, where
                            the GDPR applies, the meaning of personal data given under the GDPR. Personal information
                            generally means information which relates to a living individual who can be identified from
                            that information, or from that information and other information in a person’s possession,
                            including any expression of opinion, whether true or not, and whether recorded in material
                            form or not, about an identified or reasonably identifiable individual, and any indication
                            of intention in respect of an individual.<br>
                            Generally, the type of personal information we collect about you is the information that is
                            needed to facilitate your travel arrangements and bookings and to arrange travel related
                            services and/or products on your behalf.<br><br>
                            We therefore typically process the following types of personal information about you:
                        </p>
                        <ul class="pl-8" style="list-style: disc;">
                            <li>contact information (such as name, residential/mailing address, telephone number, email
                                address);</li>
                            <li>payment account information (credit/debit card details, including card type, card
                                number, security number and expiry date);</li>
                            <li>passport details;</li>
                            <li>loyalty program / frequent flyer details;</li>
                            <li>information about your dietary requirements and health issues (if any); and</li>
                            <li>other details relevant to your travel arrangements or required by the relevant travel
                                service provider(s) (e.g. airlines and accommodation or tour providers).</li>
                        </ul>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            When you make contact with us for other purposes, we may also collect personal information
                            about you in relation to those purposes. For example, we may collect your personal
                            information so we can contact you about a competition you have entered (e.g. if you win) or
                            to respond to an enquiry or feedback you have sent to us. We also collect information that
                            is required for use in the business activities of X-Trekkers and our related entities
                            (X-Boundaries), including for example, financial details necessary in order to process
                            various transactions, video surveillance footage used for security purposes, and other
                            relevant personal information you may elect to provide to us.<br><br>
                            In some circumstances, we may collect personal information from you which may be regarded as
                            sensitive information under your local data protection laws. Sensitive information may
                            include (without limitation) your racial or ethnic origin, philosophical or religious
                            beliefs or affiliations, sexual preferences or practices, criminal record and the alleged
                            commission of an offence, membership of political, professional or trade associations,
                            biometric and genetic information, passwords and financial information and health
                            information, and any personal information relating to a child under the age of 13. We will
                            only collect sensitive information in compliance with your local data protection laws, with
                            your explicit consent and where it is reasonably necessary for, or directly related to, one
                            or more of our functions or activities (e.g. to make travel arrangements), unless we are
                            otherwise required or authorised to do so by law. To the extent permitted or required under
                            your local data protection laws, you consent to us using and disclosing your sensitive
                            information for the purpose for which it was collected, unless we subsequently receive your
                            consent to use it for another purpose. For example, if you provide health information to us
                            in connection with a travel insurance application you would like to make, you consent to us
                            using and disclosing that health information in connection with arranging that travel
                            insurance on your behalf. A further example is if you disclose your religious beliefs to us
                            because you are interested in, for example, certain holiday packages, in which case you
                            consent to us using and disclosing that information in connection with facilitating your
                            request. We will not use sensitive information for purposes other than those for which it
                            was collected, unless we subsequently receive your consent to use it for another purpose.

                        </p>

                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            How do we collect personal information?
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We will only collect personal information in compliance with your local data protection
                            laws. We usually collect your personal information from the information you submit during
                            the course of your relationship with us. We will collect this information directly from you
                            unless it is unreasonable or impracticable to do so.<br><br>
                            Generally, this collection will occur:
                        </p>
                        <ul class="pl-8" style="list-style: disc;">
                            <li>when you deal with us either in person, by telephone, letter, email;</li>
                            <li>when you visit any of our websites; or</li>
                            <li>when you connect with us via social media.</li>
                        </ul>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We may collect personal information about you:
                        </p>
                        <ul class="pl-8" style="list-style: disc;">
                            <li>when you purchase or make enquiries about travel arrangements or other products and
                                services;</li>
                            <li>when you enter competitions or register for promotions;</li>
                            <li>when you subscribe to receive marketing from us (e.g. e-newsletters);</li>
                            <li>when you request brochures or other information from us; or </li>
                            <li>when you provide information, or use our services, on social media.</li>
                        </ul>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            Unless you choose to do so under a pseudonym or anonymously, we may also collect your
                            personal information (other than sensitive information) when you complete surveys or provide
                            us with feedback.<br>
                            In some circumstances, it may be necessary for us to collect personal information about you
                            from a third party. This includes where a person makes a travel booking on your behalf which
                            includes travel arrangements to be used by you (e.g. a family or group booking or a travel
                            booking made for you by your employer). Where this occurs, we will rely on the authority of
                            the person making the travel booking to act on behalf of any other traveller on the
                            booking.<br>
                            Where you make a travel booking on behalf of another person (e.g. a family or group booking
                            or a travel booking made for an employee), you agree you have obtained the consent of the
                            other person for X-Trekkers to collect, use and disclose the other person’s personal
                            information in accordance with this Notice and that you have otherwise made the other person
                            aware of this Notice.<br><br>
                            You should let us know immediately if you become aware that your personal information has
                            been provided to us by another person without your consent or if you did not obtain consent
                            before providing another person’s personal information to us.<br>
                            We make every effort to maintain the accuracy and completeness of your personal information
                            which we store and to ensure all of your personal information is up to date. However, you
                            can assist us with this considerably by promptly contacting us if there are any changes to
                            your personal information or if you become aware that we have inaccurate personal
                            information relating to you (see section 13 below). We will not be responsible for any
                            losses arising from any inaccurate, inauthentic, deficient or incomplete personal
                            information that you, or a person acting on your behalf, provide to us.<br>
                        </p>

                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            4. How do we use your personal information?
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We will only process your information, where:
                        </p>
                        <ul class="pl-8" style="list-style: disc;">
                            <li>you have given your consent to such processing (which you may withdraw at any time, as
                                detailed at section 8 below);</li>
                            <li>the processing is necessary to provide our services to you;</li>
                            <li>the processing is necessary for compliance with our legal obligations; and/or</li>
                            <li>the processing is necessary for our legitimate interests or those of any third party
                                recipients that receive your personal information (as detailed in sections 5 and 6
                                below).</li>
                        </ul>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            In detail: Where you contact us in relation to a travel booking or query, the purpose for
                            which we collect your personal information is generally to provide you with travel advice
                            and/or to assist you with booking travel and/or travel related products and services.
                            However, the purpose for collection may differ depending on the particular circumstances as
                            disclosed in this Notice (e.g. collection of your personal information for the purpose of
                            your participation in a competition, provision of feedback, etc.).<br>
                            When you book or otherwise arrange travel related products and services through us, we
                            usually act as an agent for the relevant travel service providers (e.g. for a hotel). In
                            this case, we process your personal information as necessary so as to provide the services
                            you requested from us. This usually includes collecting personal information about you both
                            for our internal purposes as described in this Notice and for the travel service provider
                            for whom we act as agent (e.g. to provide you with the booked services). For example, if you
                            book a flight through us, then we use your personal information to enable your flight to be
                            booked and disclose it to the airline to enable the airline to provide the flight service to
                            you.
                            We may therefore share your information with our travel service providers such as hotel,
                            airline, car rental, or other providers, who fulfill your travel bookings. Please note that
                            these travel service providers also may use your personal information as described in their
                            respective privacy policy and may contact you as necessary to obtain additional information
                            about you, facilitate your travel reservation, or provide you with your requested services.
                            We encourage you to review the privacy policies of any third-party travel service providers
                            whose products you purchase through us. We will provide you with copies of all relevant
                            travel service provider terms, conditions and privacy policies on request.<br><br>
                            The purposes for which we collect personal information further include:
                        </p>
                        <ul class="pl-8" style="list-style: disc;">
                            <li>providing you with services and tools you choose to use (for example, saving travel
                                preferences on our websites to a wishlist or saving personal information to allow for
                                pre-population of online forms);</li>
                            <li>identification of fraud or error;</li>
                            <li>regulatory reporting and compliance;</li>
                            <li>developing and improving our products and services and those of our related entities;
                            </li>
                            <li>servicing our relationship with you by, among other things, creating and maintaining a
                                customer profile to enable our brands to service you better or presenting options on our
                                website we think may interest you based on your browsing and preferences;</li>
                            <li>testimonials from your recent trip;</li>
                            <li>involving you in market research, gauging customer satisfaction and seeking feedback
                                regarding our relationship with you and/or the service we have provided;</li>
                            <li>to facilitate your participation in loyalty programs;</li>
                            <li>for research and analysis in relation to our business and services, including but not
                                limited to trends and preferences in sales and travel destinations and use of our
                                websites;</li>
                            <li>internal accounting and administration;</li>
                            <li>to comply with our legal obligations and any applicable customs/immigration requirements
                                relating to your travel; and</li>
                            <li>other purposes as authorised or required by law (e.g. to prevent a threat to life,
                                health or safety, or to enforce our legal rights).</li>

                        </ul>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            Where permitted by local data protection laws, we may use your personal information to send
                            you targeted marketing activities relating to our products and services (and those of third
                            parties) that we think may interest you, unless you have requested not to receive such
                            information. These may include, but are not limited to, mail outs, electronic marketing and
                            notifications as described below, and telephone calls). We will only use your personal
                            information to send electronic marketing materials to you (including e-newsletters, email,
                            SMS, MMS) if you have opted-in to receive them. You can subscribe to receive e-newsletters
                            and other electronic promotional/marketing materials by following the relevant links on our
                            website or requesting one of our consultants to do so for you.<br>
                            Should you no longer wish to receive promotional/marketing material from us, participate in
                            market research or receive other types of communication from us, please refer to the
                            “Feedback / Complaints / Contact” section below (section 13). You can unsubscribe from
                            receiving electronic marketing materials by following the unsubscribe prompt in your email,
                            SMS, MMS or other form of electronic marketing. Please also see the “Your rights” section of
                            this Notice to learn about your ability, at any time, to opt out or limit the use of your
                            browsing behaviour for online behavioural advertising purposes (section 8 below).
                        </p>

                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            5. Is personal information disclosed to third parties?
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We do not and will not sell, rent out or trade your personal information. We will only
                            disclose your personal information to third parties in the ways set out in this Notice and,
                            in particular, as set out below, and in accordance with your local data protection laws.
                            Note that, in this Notice, where we say “disclose”, this includes to transfer, share
                            (including verbally and in writing), send, or otherwise make available or accessible your
                            personal information to another person or entity.<br><br>
                            Your personal information may be disclosed to the following types of third parties:
                        </p>
                        <ul class="pl-8" style="list-style: disc;">
                            <li>our contractors, suppliers and service providers, including without limitation:
                                <ul class="pl-8" style="list-style: circle;">
                                    <li>in each of the circumstances set out in section 4 (“How do we use your personal
                                        information?”); </li>
                                    <li>suppliers of IT based solutions that assist us in providing products and
                                        services to you (such as any external data hosting providers we may use);</li>
                                    <li>publishers, printers and distributors of marketing material;</li>
                                    <li>event and expo organisers;</li>
                                    <li>marketing, market research, research and analysis and communications agencies;
                                    </li>
                                    <li>mailing houses, freight services, courier services; and</li>
                                    <li>external business advisers (such as lawyers, accountants, auditors and
                                        recruitment consultants);</li>
                                </ul>
                            </li>
                            <li>our related entities and brands;</li>
                            <li>travel service providers such as travel wholesalers, tour operators, airlines, hotels,
                                car rental companies, transfer handlers and other related service providers;</li>
                            <li>any third party to whom we assign any of our rights or obligations;</li>
                            <li>a person making your travel booking on your behalf, where you are travelling on a
                                booking made on your behalf by another person (for example, a family member, friend or
                                work colleague);</li>
                            <li>your employer, where you are an employee of one of our corporate, business or government
                                clients and you are participating in an event or travelling for work purposes;</li>
                            <li>a person who can verify to us that they have a relationship with you (e.g. a family
                                member) where you are not contactable, the person correctly answers our required
                                security questions and the request is, in our opinion, in your interest (for example,
                                where the person is concerned for your welfare or needs to undertake action on your
                                behalf due to unforeseen circumstances);</li>
                            <li>as required or authorised by applicable law, and to comply with our legal obligations;
                            </li>
                            <li>customs and immigration to comply with our legal obligations and any applicable
                                customs/immigration requirements relating to your travel;</li>
                            <li>government agencies and public authorities to comply with a valid and authorised
                                request, including a court order or other valid legal process;</li>
                            <li>various regulatory bodies and law enforcement officials and agencies, including to
                                protect against fraud and for related security purposes; and</li>
                            <li>enforcement agencies where we suspect that unlawful activity has been or may be engaged
                                in and the personal information is a necessary part of our investigation or reporting of
                                the matter.</li>
                        </ul>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            Other than the above, we will not disclose your personal information without your consent
                            unless we reasonably believe that disclosure is necessary to lessen or prevent a threat to
                            life, health or safety of an individual or to public health or safety or for certain action
                            to be undertaken by an enforcement body (e.g. prevention, detection, investigation,
                            prosecution or punishment of criminal offences), or where such disclosure is authorised or
                            required by law (including applicable privacy / data protection laws).<br><br>
                            On our websites, you may choose to use certain features that can be accessed through, or for
                            which we partner with, other entities that are not otherwise affiliated with us. These
                            features, which include social networking and geo-location tools, are operated by third
                            parties, including social networks, and are clearly identified as such. These third parties
                            may use or share personal information in accordance with their own privacy policies. We
                            strongly suggest you review the third parties’ privacy policies if you use the relevant
                            features.
                        </p>

                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            6. Is personal information transferred overseas?
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We may disclose your personal information to certain overseas recipients, as set out below.
                            We will ensure that any such international transfers are either necessary for the
                            performance of a contract between you and the overseas recipient or are made subject to
                            appropriate or suitable safeguards as required by your local data protection laws (e.g.
                            GDPR). We will provide you with copies of the relevant safeguard documents on request (see
                            section 13 below).<br><br>
                            It is possible that information will be transferred to an overseas recipient (other than any
                            of our overseas related entities) located in a jurisdiction where you will not be able to
                            seek redress under your local data protection laws and that does not have an equivalent
                            level of data protection as in your jurisdiction. To the extent permitted by your local data
                            protection laws, we will not be liable for how these overseas recipients handle, store and
                            process your personal information.
                        </p>
                        <h4 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            (a) Tour agents located overseas
                        </h4>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            In providing our services to you, it may be necessary for us to disclose personal
                            information to our tour agents located overseas. Our tour agents will in most cases receive
                            your personal information in the country in which they will provide the services to you or
                            where they are commonly based.
                        </p>
                        <h4 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            (b) Our third party service providers located overseas
                        </h4>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We may also disclose your personal information to third parties located overseas for the
                            purpose of performing services for us, including the storage and processing of such
                            information. Generally, we will only disclose your personal information to these overseas
                            recipients in connection with facilitation of your travel booking and/or to enable the
                            performance of administrative and technical services by them on our behalf.
                        </p>

                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            7. Security of information
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We are committed to safeguarding and protecting personal information and will implement and
                            maintain appropriate technical and organisational measures to ensure a level of security
                            appropriate to protect any personal information provided to us from accidental or unlawful
                            destruction, loss, alteration, unauthorised disclosure of, or access to personal information
                            transmitted, stored or otherwise processed. X-Trekkers has implemented various physical,
                            electronic and managerial security procedures in order to protect the personal information
                            it holds from loss and misuse, and from unauthorised access, modification, disclosure and
                            interference. X-Trekkers regularly reviews security technologies and will strive to protect
                            your personal information as fully as we protect our own confidential information.
                            X-Trekkers is not responsible for any third party’s actions or their security controls with
                            respect to information that third parties may collect or process via their websites,
                            services or otherwise.<br><br>
                            We will destroy or de-identify personal information once we no longer require it for our
                            business purposes, or as required by law.
                        </p>

                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            8. Your rights in relation to the personal information we collect
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            If you wish to:
                        </p>
                        <ul class="pl-8" style="list-style: disc;">
                           
                            <li>update, modify, delete or obtain a copy of the personal information that we hold on you; or</li>
                            <li>restrict or stop us from using any of the personal information which we hold on you, including by withdrawing any consent you have previously given to the processing of such information; or</li>
                            <li>where any personal information has been processed on the basis of your consent or as necessary to perform a contract to which you are a party, request a copy of such personal information in a suitable format</li>
                           
                        </ul>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            you can request this by emailing us at the address set out in section 13 below. You will receive acknowledgement of your request and we will advise you of the timeframe within which you will receive your information pack.<br>
                            We endeavour to respond to such requests within a month or less, although we reserve the right to extend this period for complex requests.<br>
                            We reserve the right to deny you access for any reason permitted under applicable laws. Such exemptions may include national security, corporate finance and confidential references. If we deny access or correction, we will provide you with written reasons for such denial unless it is unreasonable to do so and, where required by local data protection laws, will note your request and the denial of same in our records.<br>
                            Further correspondence regarding your request should only be made in writing to the Data Protection Officer at the address set out in section 13 below.
                        </p>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            Please note that, if you request that we restrict or stop using personal information we hold on you, or withdraw a consent you have previously given to the processing of such information, this may affect our ability to provide services to you or negatively impact the services we can provide to you. For example, most travel bookings must be made under the traveller’s full name and must include contact details and appropriate identification (e.g. passport details). We cannot make bookings for you without that information.<br>
                            You must always provide accurate information and you agree to update it whenever necessary.  You also agree that, in the absence of any update, we can assume that the information submitted to us is correct, unless we subsequently become aware that it is not correct.<br>
                            You can at any time tell us not to send you marketing communications by email by clicking on the unsubscribe link within the marketing emails you receive from us or by contacting us as indicated below (section 13).<br>
                            In any of the situations listed above, we may request that you prove your identity by providing us with a copy of a valid means of identification in order for us to comply with our security obligations and to prevent unauthorised disclosure of personal information.<br>
                            We reserve the right to charge you a reasonable administrative fee for any manifestly unfounded or excessive requests concerning your access to your personal information, and for any additional copies of the personal information you request from us.
                        </p>

                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            9. Social Media Integrations
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            Our websites and mobile applications may use social media features and widgets (such as “Like” and “Share” buttons/widgets) (“<b>SM Features</b>”). These are provided and operated by third party companies (e.g. Facebook) and either hosted by a third party or hosted directly on our website or mobile application. SM Features may collect information such as the page you are visiting on our website/mobile application, your IP address, and may set cookies to enable the SM Feature to function properly.
                        </p>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            If you are logged into your account with the third party company, then the third party may be able to link information about your visit to and use of our website or mobile application to your social media account with them. Similarly, your interactions with the SM Features may be recorded by the third party. In addition, the third party company may send us information in line with their policies, such as your name, profile picture, gender, friend lists and any other information you have chosen to make available, and we may share information with the third party company for the purposes of serving targeted marketing to you via the third party social media platform. You can manage the sharing of information and opt out from targeted marketing via your privacy settings for the third party social media platform.
                        </p>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            Your interactions with these SM Features are governed by the privacy policy of the third party company providing them. For more information about the data practices of these third party companies, and to find out more about what personal information is collected about you and how the third party uses such personal information, please refer to their privacy policy directly.
                        </p>
                        
                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            10. IP addresses
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            When you access our website, use any of our mobile applications or open electronic correspondence or communications from us, our servers may record data regarding your device and the network you are using to connect with us, including your IP address. An IP address is a series of numbers which identify your computer, and which are generally assigned when you access the internet.
                        </p>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We may use IP addresses for system administration, investigation of security issues and compiling anonymised data regarding usage of our website and/or mobile applications. We may also link IP addresses to other personal information we hold about you and use it for the purposes described above (e.g. to better tailor our marketing and advertising materials, provided you have opted in to receive electronic marketing).
                        </p>
                        
                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            11. Tracking Technologies / Cookies
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We may use third-party web analytics services on our websites and mobile apps, such as those listed in our Cookies Policy. The analytics providers that administer these services use technologies such as cookies and web beacons to help us analyse how visitors use our websites and apps.
                        </p>
                        
                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            12. Linked Sites
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            Our websites may contain links to third party websites over which we have no control. We are not responsible for the privacy practices or the content of such websites. We encourage you to read the privacy policies of any linked third party websites you visit as their privacy policy and practices may differ from ours.
                        </p>
                        
                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            13. Feedback / Complaints / Contact 
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            If you wish to make a request to inform us of a change or correction to your personal information, request a copy of the information we collect on you, request deletion of your information or would like to restrict the further processing of your data, please email us at 
                            <NuxtLink to="mailto:info@x-trekkers.com." class="underline"> info@x-trekkers.com.</NuxtLink>
                        </p>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We will respond to these requests within a month of receipt.
                        </p>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            If you have any enquiries, comments or complaints about this Notice or our handling of your personal information, please also email us at 
                            <NuxtLink to="mailto:info@x-trekkers.com." class="underline"> info@x-trekkers.com.</NuxtLink> and we will respond as soon as we are able to.
                        </p>
                        
                    </div>
                    <div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-white mt-4">
                            14. Changes to our Notice
                        </h3>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            We may amend this Notice from time to time. If we make a change to the Notice, the revised version will be posted on our website. We will post a prominent notice on our website to notify you of any significant changes to our Notice and indicate at the end of the Notice when it was most recently updated. It is your responsibility, and we encourage you, to check the website from time to time in order to determine whether there have been any changes. If we update our Notice, in certain circumstances, we may seek your consent.
                        </p>
                        <p class="text-white text-base whitespace-pre-line leading-relaxed mt-4">
                            This Privacy Notice was last updated on 24 May 2018.
                        </p>
                       
                    </div>


                </div>
            </section>

            <PageFooter :footerData="headerFooter" />
        </article>
    </div>
</template>

<style scoped>
p{
    text-align: justify;
}
</style>