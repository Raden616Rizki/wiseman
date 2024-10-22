<template>
  <Layout>
    <div class="d-flex">
      <div class="m-3 left-card">
        <div class="card main-bg p-3">
          <h6 class="font-4 mb-3">Calendar</h6>
          <DatePicker v-model="date" inline class="w-full sm:w-[30rem]" dateFormat="yy-mm-dd" @update:modelValue="changeDate"/>
        </div>
        <div class="card main-bg p-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="font-4 mb-0">Memo</h6>
            <i class="bx bx-plus memo-bold-font" style="color: #EEEEEE; font-size: 16px;"></i>
          </div>
          <div class="card bg-white p-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <p class="mb-0 memo-bold-font">Group Alpha</p>
              <i class="bx bx-dots-vertical-rounded memo-bold-font" style="font-size: 16px;"></i>
            </div>
            <div>
              <p>Pesan Memo</p>
            </div>
            <div>
              <p class="memo-bold-font mb-0 d-flex justify-content-end">08:49 06/09/2024</p>
            </div>
          </div>
        </div>
      </div>
      <div class="m-3 right-card">
        <div class="card main-bg p-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="font-4 mb-0">Activity</h6>
            <i class="bx bx-plus memo-bold-font" style="color: #EEEEEE; font-size: 16px; cursor: pointer"
              @click="openActivityFormModal('add')"></i>
          </div>
          <table v-for="activity in activities" :key="activity.id" class="table align-middle" :style="{
            borderRadius: '4px',
            backgroundColor: activity.is_priority === 1 ? '#067e82' : 'white',
            overflow: 'hidden',
            borderCollapse: 'separate',
          }">
            <tr>
              <td style="text-align: center; width: 80px;">
                <div class="d-flex flex-column align-items-center activity-time" :style="{
                  backgroundColor: activity.is_priority === 1 ? '#067e82' : 'white',
                  color: activity.is_priority === 1 ? 'white' : '',
                }">
                  <p class="mb-0"> {{ activity.start_time.substr(11, 5) }} </p>
                  <p class="mb-0">-</p>
                  <p class="mb-0"> {{ activity.end_time.substr(11, 5) }} </p>
                </div>
              </td>
              <td style="width: 100%;">
                <p class="activity-description mb-0" :style="{
                  backgroundColor: activity.is_priority === 1 ? '#067e82' : 'white',
                  color: activity.is_priority === 1 ? 'white' : '',
                }"> {{ activity.description }} </p>
              </td>
              <td style="text-align: center; width: 50px;">
                <input class="form-check-input activity-check me-2" type="checkbox"
                  :id="'flexCheckChecked-' + activity.id"
                  @change="finishActivity(activity.id, $event.target.checked ? 1 : 0)"
                  :checked="activity.is_finished === 1" style="border: 2px solid #303841;">
              </td>
              <td style="text-align: center; width: 40px;">
                <div class="d-flex justify-content-center align-items-center" :style="{
                  backgroundColor: activity.is_priority === 1 ? '#067e82' : 'white',
                  color: activity.is_priority === 1 ? 'white' : '',
                }">
                  <i class="bx bx-edit mt-1" @click="openActivityFormModal(activity.id)" v-b-tooltip.hover
                    title="Update activity" style="font-size: 14px; cursor: pointer;"></i>
                  <i class="bx bx-trash ms-1 mt-1" @click="deleteActivity(activity.id)" v-b-tooltip.hover
                    title="Delete activity" style="font-size: 14px; cursor: pointer;"></i>
                </div>
              </td>
            </tr>
          </table>
        </div>

        <!-- ========== Activity Modal ========== -->
        <BModal v-model="isActivityFormOpen" id="modal-standard" :title="activityFormTitle + ' Activity'"
          title-class="font-18" :ok-title="activityFormTitle" @ok="saveActivity" @hide.prevent
          @cancel="isActivityFormOpen = false" @close="isActivityFormOpen = false">
          <BRow>
            <BCol cols="12" class="mt-2">
              <BForm class="form-horizontal" role="form">
                <BRow class="mb-3">
                  <BCol>
                    <textarea class="form-control" :class="{
                      'is-invalid': !!(activityErrorList && activityErrorList.description),
                    }" id="form-activity-description" type="text" placeholder="Masukkan deskripsi aktivitas ..."
                      v-model="activityForm.description" required />

                    <template v-if="!!(activityErrorList && activityErrorList.description)">
                      <div class="invalid-feedback" v-for="(err, index) in activityErrorList.description" :key="index">
                        <span>{{ err }}</span>
                      </div>
                    </template>
                  </BCol>
                </BRow>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                    <BCol md="5">
                      <input class="form-control" :class="{
                        'is-invalid': !!(activityErrorList && activityErrorList.start_time),
                      }" id="form-start-activity" placeholder="Start time" v-model="activityForm.start_time"
                        type="time" required />
                      <template v-if="!!(activityErrorList && activityErrorList.start_time)">
                        <div class="invalid-feedback" v-for="(err, index) in activityErrorList.start_time" :key="index">
                          <span>{{ err }}</span>
                        </div>
                      </template>
                    </BCol>
                    <BCol md="0">
                      <p class="activity-time mb-0 mx-2">-</p>
                    </BCol>
                    <BCol md="5">
                      <input class="form-control" :class="{
                        'is-invalid': !!(activityErrorList && activityErrorList.end_time),
                      }" id="form-end-activity" placeholder="End ime" v-model="activityForm.end_time" type="time"
                        required />
                      <template v-if="!!(activityErrorList && activityErrorList.end_time)">
                        <div class="invalid-feedback" v-for="(err, index) in activityErrorList.end_time" :key="index">
                          <span>{{ err }}</span>
                        </div>
                      </template>
                    </BCol>
                  </div>
                  <BCol md="2 d-flex">
                    <input class="form-check-input activity-check-form" type="checkbox" id="form-priority-activity"
                      @change="updatePriority" :checked="activityForm.is_priority === 1 || false" />
                    <label class="form-check-label mt-1 ms-1" for="form-priority-activity">
                      Prioritas
                    </label>
                  </BCol>
                </div>
              </BForm>
            </BCol>
          </BRow>
        </BModal>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from "../../layouts/main";
import DatePicker from 'primevue/datepicker';
import { ref, reactive, onMounted, computed } from "vue";
import { useActivityStore, useAuthStore } from "@/state/pinia";
import { useProgress } from "@/helpers/progress";
import {
  showSuccessToast,
  showErrorToast,
  showDeleteConfirmationDialog,
} from "@/helpers/alert.js";

const { startProgress, finishProgress, failProgress } = useProgress();

const activityStore = useActivityStore();
const activities = ref([]);

const activityStatusCode = computed(() => activityStore.response.status);
const activityErrorList = computed(() => activityStore.response?.error || {});
const activityErrorMessage = computed(() => activityStore.response?.message || "");

const authStore = useAuthStore();
const user = authStore.getUser();
const userId = user.id;

const date = ref('');
const isActivityFormOpen = ref(false);
const activityFormTitle = ref("Create");

activityStore.userId = userId;

const activityForm = reactive({
  id: "",
  group_id: "",
  user_id: user.id,
  description: "",
  start_time: "",
  end_time: "",
  is_priority: 0,
  is_finished: 0,
});

const openActivityFormModal = async (activityId) => {
  isActivityFormOpen.value = true;
  if (activityId != 'add') {
    const activity = await activityStore.getActivityById(activityId);

    activityForm.id = activity.id;
    activityForm.group_id = activity.group_id;
    activityForm.user_id = activity.user_id;
    activityForm.description = activity.description;
    activityForm.start_time = activity.start_time.substr(11, 5);
    activityForm.end_time = activity.end_time.substr(11, 5);
    activityForm.is_priority = activity.is_priority;
    activityForm.is_finished = activity.is_finished;

    activityFormTitle.value = 'Update';
  } else {
    activityForm.id = '';
    activityForm.group_id = '';
    activityForm.user_id = user.id;
    activityForm.description = '';
    activityForm.start_time = '';
    activityForm.end_time = '';
    activityForm.is_priority = 0;
    activityForm.is_finished = 0;

    activityFormTitle.value = 'Create';
  }
}

const updatePriority = (event) => {
  activityForm.is_priority = event.target.checked ? 1 : 0;
};

const getFormattedTime = (dateData, timeData) => {
  return `${dateData} ${timeData}:00`;
};

const getFormattedDate = (dateData) => {
  const year = dateData.getFullYear();
  const month = String(dateData.getMonth() + 1).padStart(2, '0');
  const day = String(dateData.getDate()).padStart(2, '0');

  return `${year}-${month}-${day}`;
};

const changeDate = async (dateData) => {
  const oldFormattedDate = dateData;
  date.value = getFormattedDate(dateData);
  activityStore.startTime = date.value;
  activityStore.endTime = date.value;

  await getActivities();
  date.value = oldFormattedDate;
}

const getActivities = async () => {
  startProgress();
  await activityStore.getActivities();

  if (activityStore.activities) {
    activities.value = activityStore.activities;
    finishProgress()
  } else {
    failProgress();
  }
}

const saveActivity = async () => {
  startProgress();
  try {
    activityForm.start_time = getFormattedTime(date.value, activityForm.start_time);
    activityForm.end_time = getFormattedTime(date.value, activityForm.end_time);
    if (activityForm.id) {
      await activityStore.updateActivity(activityForm);
      if (activityStatusCode.value != 200) {
        failProgress();
        showErrorToast("Failed to update activity", activityErrorMessage);
      } else {
        isActivityFormOpen.value = false;
        finishProgress();
        showSuccessToast("Activity updated successfully!");
      }
    } else {
      console.log(date.value);
      console.log(activityForm);
      await activityStore.addActivities(activityForm);
      if (activityStatusCode.value != 200) {
        failProgress();
        showErrorToast("Failed to add activity", activityErrorMessage);
      } else {
        isActivityFormOpen.value = false;

        finishProgress();
        showSuccessToast("Activity added successfully!");
      }
    }
  } catch (error) {
    console.error(error);
    showErrorToast("Failed to saved activity", activityErrorMessage);
    failProgress();
  }

  await getActivities();
}

const finishActivity = async (activityId, isFinished) => {
  try {
    const activity = await activityStore.getActivityById(activityId);

    activityForm.id = activity.id;
    activityForm.group_id = activity.group_id;
    activityForm.user_id = activity.user_id;
    activityForm.description = activity.description;
    activityForm.start_time = activity.start_time.substr(11, 5);
    activityForm.end_time = activity.end_time.substr(11, 5);
    activityForm.is_priority = activity.is_priority;
    activityForm.is_finished = isFinished;

    await saveActivity();
  } catch (error) {
    console.error(error);
    showErrorToast("Failed to finish activity", activityErrorMessage);
  }
}

const deleteActivity = async (activityId) => {
  const confirmed = await showDeleteConfirmationDialog();

  if (confirmed) {
    startProgress();
    try {
      await activityStore.deleteActivity(activityId);
      await getActivities();

      finishProgress();
      showSuccessToast("Delete activity successfully");
    } catch (error) {
      console.error(error);
      failProgress();
      showSuccessToast("Delete activity failed");
    }
  }
}

onMounted(async () => {
  const today = new Date().toISOString().slice(0, 10);
  date.value = today
  activityStore.startTime = date.value;
  activityStore.endTime = date.value;

  await getActivities();
})

</script>

<style scoped>
.activity-check {
  width: 24px;
  height: 24px;
}

.activity-check-form {
  width: 20px;
  height: 20px;
}

.activity-description {
  font-size: 14px;
}

.activity-time {
  font-weight: bold;
  font-size: 12px;
}

.left-card {
  width: 40%;
}

.main-bg {
  background-color: #3A4750;
  box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.25);
}

.memo-bold-font {
  font-weight: bold;
  font-size: 13px;
}

.font-4 {
  font-weight: bold;
  color: #EEEEEE
}

.right-card {
  width: 55%;
}
</style>