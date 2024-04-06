
<template>
  <vue-cal
  selected-date="2024-04-03"
  :time-from="9 * 60"
  :time-to="19 * 60"
  :disable-views="['years', 'year']"
  :snap-to-time="20"
  :events="events"
  :on-event-click="onEventClick"
  :on-event-create="onEventCreate"
  :editable-events="{ title: false, drag: false, resize: true, delete: true, create: true }"
  :drag-to-create-threshold="0"
  @event-drag-create="showEventCreationDialog = true"
  >
</vue-cal>


    <el-card class="box-card " v-if="showDialog">
      <div slot="header" class="clearfix">
        <el-icon :name="selectedEvent.icon"></el-icon>
        <span>{{ selectedEvent.title }}</span>
        <div style="float: right;">
          <strong>{{ selectedEvent.start && selectedEvent.start.format('DD/MM/YYYY') }}</strong>
        </div>
      </div>
      <div v-html="selectedEvent.contentFull"></div>
      <strong>Event details:</strong>
      <ul>
        <li>Event starts at: {{ selectedEvent.start && selectedEvent.start.formatTime() }}</li>
        <li>Event ends at: {{ selectedEvent.end && selectedEvent.end.formatTime() }}</li>
      </ul>
    </el-card>

</template>


<script>
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'

export default {
  components: { VueCal },
  data(){
    return {

      showEventCreationDialog: false,
      selectedEvent: {},
      showDialog: true,
      events: [
      {
        start: '2024-04-03 14:00',
        end: '2024-04-03 18:00',
        title: 'Need to go shopping',
        content: 'Click to see my shopping list',
        contentFull: 'My shopping list is rather long:<br><ul><li>Avocados</li><li>Tomatoes</li><li>Potatoes</li><li>Mangoes</li></ul>', // Custom attribute.
        class: 'leisure'
      },
      {
        start: '2024-04-03 10:00',
        end: '2024-04-03 15:00',
        title: 'Golf with John',
        content: 'Do I need to tell how many holes?',
        contentFull: 'Okay.<br>It will be a 18 hole golf course.', // Custom attribute.
        class: 'sport'
      }
    ]
  }
},
 
  methods: {
    onEventCreate (event, deleteEventFunction) {
      this.selectedEvent = event
      this.deleteEventFunction = deleteEventFunction
      console.log('Event created:', event)
      return event
  },
    onEventClick (event, e) {
      console.log('Event clicked:', event);

      this.selectedEvent = event
      this.showDialog = true

      // Prevent navigating to narrower view (default vue-cal behavior).
      e.stopPropagation()
    }
  }
}

</script>
<style >
.vuecal__event {cursor: pointer;}

.vuecal__event-title {
  font-size: 1.2em;
  font-weight: bold;
  margin: 4px 0 8px;
}

.vuecal__event-time {
  display: inline-block;
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.2);
}
.vuecal__event--dragging {background-color: rgba(60, 60, 60, 0.3);}
.vuecal__event-content {
  font-style: italic;
}
.vuecal__event-delete {
  background-color: #3d2fffd9 !important;
}
.vuecal__event {
  color: white !important;
  background-color: #ff0000cc !important;
}
</style>


