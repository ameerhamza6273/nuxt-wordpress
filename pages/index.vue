<template>
    <div>
        <pageHeader />
        <HeroSection />
        <AboutUs />
        <SignMeSec />
        <Trips />
        <ExploreTrip />
        <FindAdventure />
        <post />
        <ReviewsSec />
        <ContactUs />
        <pageFooter />
    </div>
</template>

<script>
import pageHeader from '@/components/pageHeader.vue';
import HeroSection from '@/components/HeroSection.vue';
import AboutUs from '@/components/AboutUs.vue';
import SignMeSec from '@/components/SignMeSec.vue';
import Trips from '@/components/Trips.vue';
import ExploreTrip from '@/components/ExploreTrip.vue';
import FindAdventure from '@/components/FindAdventure.vue';
import post from '@/components/post.vue';
import ReviewsSec from '@/components/ReviewsSec.vue';
import ContactUs from '@/components/ContactUs.vue';
import pageFooter from '@/components/pageFooter.vue';

export default {
    components: {
        pageHeader,
        HeroSection,
        AboutUs,
        SignMeSec,
        Trips,
        SignMeSec,
        SignMeSec,
        post,
        ReviewsSec,
        ContactUs,
        pageFooter
    }
};
</script>
