<template>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold mb-4">{{ $t('auto.key25') }}</h1>
        <TrainingCalendar />
        <SessionEditor />
        <MatchPreparation />
        <PlayerLoadMonitor />
        <AttendanceTracker />
    </div>
</template>
<script>
import TrainingCalendar from "../components/TrainingCalendar.vue";
import SessionEditor from "../components/SessionEditor.vue";
import MatchPreparation from "../components/MatchPreparation.vue";
import PlayerLoadMonitor from "../components/PlayerLoadMonitor.vue";
import AttendanceTracker from "../components/AttendanceTracker.vue";

export default {
    name: "RPMPortal",
    components: {
        TrainingCalendar,
        SessionEditor,
        MatchPreparation,
        PlayerLoadMonitor,
        AttendanceTracker,
    },
};
</script>
