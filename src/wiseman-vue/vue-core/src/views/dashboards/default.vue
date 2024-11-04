<template>
  <Layout>
    <div class="d-flex">
      <div class="m-3 left-card">
        <div class="card main-bg p-3">
          <h6 class="font-4 mb-3">Calendar</h6>
          <DatePicker v-model="date" inline class="w-full sm:w-[30rem]" dateFormat="yy-mm-dd"
            @update:modelValue="changeDate" />
        </div>
        <div v-if="groupId" class="card main-bg p-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="font-4 mb-0">Memo</h6>
            <i v-if="isAdmin" class="bx bx-plus memo-bold-font"
              style="color: #EEEEEE; font-size: 16px; cursor: pointer;" @click="openMemoFormModal('add')"></i>

            <!-- ========== Memo Modal ========== -->
            <BModal v-model="isMemoFormOpen" id="modal-standard" :title="memoFormTitle + ' Memo'" title-class="font-18"
              :ok-title="memoFormTitle" @ok="saveMemo" @hide.prevent @cancel="isMemoFormOpen = false"
              @close="isMemoFormOpen = false" :ok-disabled="!memoForm.message">
              <BRow>
                <BCol cols="12" class="mt-2">
                  <BForm class="form-horizontal" role="form">
                    <BRow class="mb-3">
                      <BCol>
                        <textarea class="form-control" id="form-memo-message" type="text"
                          placeholder="Masukkan pesan memo ..." v-model="memoForm.message" required />
                      </BCol>
                    </BRow>
                  </BForm>
                  <BRow>
                    <BCol>
                      <BFormSelect v-model="memoForm.group_id" class="w-100 custom-select">
                        <option disabled value="">Pilih Group</option>
                        <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.name }}</option>
                      </BFormSelect>
                    </BCol>
                  </BRow>
                </BCol>
              </BRow>
            </BModal>
          </div>
          <div style="height: 250px; overflow-y: auto; padding-right: 10px"
            @contextmenu.prevent="openMemoFormModal('add')">
            <div v-for="memo in memos" :key="memo.id" class="card bg-white p-2">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <p class="mb-0 memo-bold-font"> {{ memo.groupName }} </p>
                <div v-if="isAdmin">
                  <i class="bx bx-edit mt-1" v-b-tooltip.hover title="Update memo"
                    style="font-size: 16px; cursor: pointer;" @click="openMemoFormModal(memo)"></i>
                  <i class="bx bx-trash ms-1 mt-1" v-b-tooltip.hover title="Delete memo"
                    style="font-size: 16px; cursor: pointer;" @click="deleteMemo(memo.id, memo.memoId)"></i>
                </div>
              </div>
              <div>
                <p> {{ memo.message }} </p>
              </div>
              <div class="d-flex justify-content-end">
                <p class="mb-0"> {{ memo.time }} </p>
                <p class="memo-bold-font mb-0 ms-2"> {{ memo.date }} </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="m-3 right-card">
        <div class="card main-bg">
          <div class="d-flex justify-content-between align-items-center m-3">
            <h6 class="font-4 mb-0">Activity</h6>
            <div>
              <i v-if="groupId && isAdmin" class="bx bxs-bar-chart-alt-2 memo-bold-font mt-1 me-4"
                style="color: #EEEEEE; font-size: 16px; cursor: pointer" @click="openVotingFormModal('add')"
                v-b-tooltip.hover title="Create voting"></i>
              <i v-if="!groupId || isAdmin" class="bx bx-task memo-bold-font mt-1"
                style="color: #EEEEEE; font-size: 16px; cursor: pointer" @click="openActivityFormModal('add')"
                v-b-tooltip.hover title="Create acitvity"></i>
            </div>
          </div>
          <div class="m-3" style="height: 627px; overflow-y: auto; padding-right: 10px"
            @contextmenu.prevent="openActivityFormModal('add')">
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
                  <p v-if="activity.group_id" class="activity-description mb-0" :style="{
                    backgroundColor: activity.is_priority === 1 ? '#067e82' : 'white',
                    color: activity.is_priority === 1 ? 'white' : '',
                    fontWeight: 'bold',
                  }"> {{ activity.group.name }} </p>
                  <p class="activity-description mb-0" :style="{
                    backgroundColor: activity.is_priority === 1 ? '#067e82' : 'white',
                    color: activity.is_priority === 1 ? 'white' : '',
                  }"> {{ activity.description }} </p>
                </td>
                <td style="text-align: center; width: 50px;">
                  <input v-if="!groupId || isAdmin" class="form-check-input activity-check me-2" type="checkbox"
                    :id="'flexCheckChecked-' + activity.id"
                    @change="finishActivity(activity.id, $event.target.checked ? 1 : 0)"
                    :checked="activity.is_finished === 1"
                    :style="{ border: activity.is_priority === 1 ? '2px solid #EEEEEE' : '2px solid black' }">
                </td>
                <td style="text-align: center; width: 40px;">
                  <div v-if="!groupId || isAdmin" class="d-flex justify-content-center align-items-center" :style="{
                    backgroundColor: activity.is_priority === 1 ? '#067e82' : 'white',
                    color: activity.is_priority === 1 ? 'white' : '',
                  }">
                    <i class="bx bx-edit mt-1" @click="openActivityFormModal(activity)" v-b-tooltip.hover
                      title="Update activity" style="font-size: 14px; cursor: pointer;"></i>
                    <i class="bx bx-trash ms-1 mt-1" @click="deleteActivity(activity.id)" v-b-tooltip.hover
                      title="Delete activity" style="font-size: 14px; cursor: pointer;"></i>
                  </div>
                </td>
              </tr>
            </table>
            <table v-for="votingData in votings" :key="votingData.id" class="table align-middle bg-white" :style="{
              borderRadius: '4px',
              overflow: 'hidden',
              borderCollapse: 'separate',
            }">
              <tr>
                <td style="text-align: center; width: 80px;">
                  <div class="d-flex flex-column align-items-center voting-time bg-white">
                    <p class="mb-0"> Limit </p>
                    <p class="mb-0">-</p>
                    <p class="mb-0"> {{ votingData.limitTime.substr(11, 5) }} </p>
                  </div>
                </td>
                <td style="width: 100%;">
                  <p v-if="votingData.groupId" class="voting-description mb-0 bg-white" :style="{
                    fontWeight: 'bold',
                  }"> {{ votingData.group.name }} </p>
                  <p class="voting-description mb-0 bg-white"> {{ votingData.description }} </p>
                </td>
                <td style="text-align: center; width: 50px;">
                  <button class="btn votting-button align-items-center me-3"
                    @click="openVotingModal(votingData)">Voting</button>
                </td>
                <td v-if="isAdmin" style="text-align: center; width: 40px;">
                  <div class="d-flex justify-content-center align-items-center bg-white">
                    <i class="bx bx-edit mt-1" @click="openVotingFormModal(votingData)" v-b-tooltip.hover
                      title="Update voting" style="font-size: 14px; cursor: pointer;"></i>
                    <i class="bx bx-trash ms-1 mt-1" @click="deleteVoting(votingData.id)" v-b-tooltip.hover
                      title="Delete voting" style="font-size: 14px; cursor: pointer;"></i>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <!-- ========== Activity Modal ========== -->
        <BModal v-model="isActivityFormOpen" id="modal-standard" :title="activityFormTitle + ' Activity'"
          title-class="font-18" :ok-title="activityFormTitle" @ok="saveActivity" @hide.prevent
          @cancel="isActivityFormOpen = false" @close="isActivityFormOpen = false"
          :ok-disabled="!activityForm.description || !activityForm.start_time || !activityForm.end_time">
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
                    <BCol md="6">
                      <input class="form-control" :class="{
                        'is-invalid': !!(activityErrorList && activityErrorList.start_time),
                      }" id="form-start-activity" placeholder="Start time" v-model="activityForm.start_time"
                        type="time" step="300" required />
                      <template v-if="!!(activityErrorList && activityErrorList.start_time)">
                        <div class="invalid-feedback" v-for="(err, index) in activityErrorList.start_time" :key="index">
                          <span>{{ err }}</span>
                        </div>
                      </template>
                    </BCol>
                    <BCol md="0">
                      <p class="activity-time mb-0 mx-2">-</p>
                    </BCol>
                    <BCol md="6">
                      <input class="form-control" :class="{
                        'is-invalid': !!(activityErrorList && activityErrorList.end_time),
                      }" id="form-end-activity" placeholder="End ime" v-model="activityForm.end_time" type="time"
                        required />
                      <template v-if="!!(activityErrorList && activityErrorList.end_time)">
                        <div class="invalid-feedback" v-for="(err, index) in activityErrorList.end_time" :key="index">
                          <span>{{ err }}</span>
                        </div>
                      </template>
                      <!-- <template v-if="endTimeError">
                        <div class="invalid-feedback">
                          <span>End time cannot be earlier than start time.</span>
                        </div>
                      </template> -->
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

        <!-- ========== Voting Modal ========== -->
        <BModal v-model="isVotingFormOpen" id="modal-standard" :title="votingFormTitle + ' Voting'"
          title-class="font-18" :ok-title="votingFormTitle" @ok="saveVoting" @hide.prevent
          @cancel="isVotingFormOpen = false" @close="isVotingFormOpen = false"
          :ok-disabled="!votingForm.description || votingForm.voting_options.length < 2 || !votingForm.limit_time">
          <BRow>
            <BCol cols="12" class="mt-2">
              <BForm class="form-horizontal" role="form">
                <BRow class="mb-3">
                  <BCol>
                    <textarea class="form-control" :class="{
                      'is-invalid': !!(votingErrorList && votingErrorList.description),
                    }" id="form-voting-description" type="text" placeholder="Masukkan deskripsi voting ..."
                      v-model="votingForm.description" required />

                    <template v-if="!!(votingErrorList && votingErrorList.description)">
                      <div class="invalid-feedback" v-for="(err, index) in votingErrorList.description" :key="index">
                        <span>{{ err }}</span>
                      </div>
                    </template>
                  </BCol>
                </BRow>
                <BRow class="mb-3">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="col-md-2 col-form-label" for="form-option">Opsi Voting</label>
                    <i class="bx bx-plus memo-bold-font" style="font-size: 16px; cursor: pointer;"
                      @click="addOption"></i>
                  </div>

                  <BCol>
                    <div v-for="(option, index) in votingForm.voting_options" :key="index"
                      class="d-flex justify-content-between align-items-center mb-3">
                      <i v-if="option.id" class="bx bx-minus memo-bold-font me-2"
                        style="font-size: 16px; cursor: pointer;" @click="deleteOption(option.id, index)"></i>
                      <i v-else class="bx bx-minus memo-bold-font me-2" style="font-size: 16px; cursor: pointer;"
                        @click="deleteOption('default', index)"></i>
                      <input class="form-control" type="text" placeholder="Masukkan opsi voting" v-model="option.option"
                        @input="markAsUpdated(index)">
                    </div>
                  </BCol>
                </BRow>
                <BRow class="mb-3">
                  <BCol class="d-flex align-items-center">
                    <label class="col-form-label" for="form-limit-time">Limit Time</label>
                    <input class="form-control" :class="{
                      'is-invalid': !!(votingErrorList && votingErrorList.limit_time),
                    }" id="form-start-voting" placeholder="Limit time" v-model="votingForm.limit_time" type="time"
                      required />
                    <template v-if="!!(votingErrorList && votingErrorList.limit_time)">
                      <div class="invalid-feedback" v-for="(err, index) in votingErrorList.limit_time" :key="index">
                        <span>{{ err }}</span>
                      </div>
                    </template>
                  </BCol>
                </BRow>
              </BForm>
            </BCol>
          </BRow>
        </BModal>

        <!-- ========== Open Voting Modal ========== -->
        <BModal v-model="isVotingOpen" id="modal-standard" :title="votingForm.description" title-class="font-18"
          @hide.prevent @cancel="isVotingOpen = false" @close="isVotingOpen = false" size="sm" ok-title="Voting Result"
          @ok="openVotingResult">
          <BRow>
            <BCol cols="12" class="mt-2">
              <BForm class="form-horizontal" role="form">
                <BRow v-for="(option, index) in votingForm.voting_options" :key="option.id">
                  <div class="form-check mb-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 border border-dark px-2 py-1 rounded w-100"
                      :class="{ 'selected-option': selectedOption === option.id, 'default-option': selectedOption !== option.id }">
                      {{ option.option }}</h6>

                    <input v-if="isBeforeLimitTime" class="form-check-input ms-3 mt-0" type="radio"
                      :id="'option-' + index" :value="option.id" name="votingOptionsGroup"
                      @change="chooseOption(option.id, option.votingId)" :disabled="hasVoted" />
                  </div>
                </BRow>
              </BForm>
            </BCol>
          </BRow>
        </BModal>

        <!-- ========== Voting Result Modal ========== -->
        <BModal v-model="isVotingResultOpen" id="modal-standard" :title="votingForm.description" title-class="font-18"
          @hide.prevent @cancel="isVotingResultOpen = false" @close="isVotingResultOpen = false" ok-title="Back"
          @ok="openVotingModal(voting)">
          <BRow>
            <Chart type="bar" :data="chartData" :options="chartOptions" />
          </BRow>
        </BModal>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from "../../layouts/main";
import DatePicker from 'primevue/datepicker';
import { ref, reactive, onMounted, onUnmounted, computed, watch } from "vue";
import {
  useActivityStore,
  useAuthStore,
  useGroupStore,
  useVotingStore,
  useMemoStore,
  useUserVotingStore
} from "@/state/pinia";
import { useProgress } from "@/helpers/progress";
import {
  showSuccessToast,
  showErrorToast,
  showDeleteConfirmationDialog,
} from "@/helpers/alert.js";
import { useRoute } from "vue-router";
import Chart from 'primevue/chart';

const route = useRoute();
const groupId = ref(route.query.id);
const isAdmin = ref(false);
const hasVoted = ref(false);
// const endTimeError = ref(false);

watch(() => route.query.id, async (newVal) => {
  groupId.value = newVal;

  if (!groupId.value) {
    activityStore.userId = userId;
    groupId.value = '';
    votings.value = [];
  } else {
    activityStore.userId = '';
    isAdmin.value = await checkIsAdminById(groupId.value);
  }

  activityStore.groupId = groupId.value;
  votingStore.groupId = groupId.value;

  await getActivities();

  if (groupId.value) {
    startProgress();
    await getVotings();
    finishProgress();
  }

  getMemos();
});

const { startProgress, finishProgress, failProgress } = useProgress();

const activityStore = useActivityStore();
const activities = ref([]);

const activityStatusCode = computed(() => activityStore.response.status);
const activityErrorList = computed(() => activityStore.response?.error || {});
const activityErrorMessage = computed(() => activityStore.response?.message || "");

const authStore = useAuthStore();
const user = authStore.getUser();
const userId = user.id;

const groupStore = useGroupStore();
const groups = computed(() => {
  return user.groupUsers.map(groupUser =>
    groupUser.group
  ) || [];
});

const checkIsAdmin = (group) => {
  const isAdmin = group.groupDetails.some(
    (detail) => detail.user.id === userId && detail.isAdmin === 1
  );

  return isAdmin;
};

const checkIsAdminById = async (groupId) => {
  const group = await groupStore.getGroupById(groupId);
  const isAdmin = checkIsAdmin(group);

  return isAdmin;
}

const isBeforeLimitTime = computed(() => {
  if (!voting.value.limitTime) return false;

  const now = new Date();
  const limitDateTime = new Date(voting.value.limitTime.replace(' ', 'T'));
  return now < limitDateTime;
});

const votingStore = useVotingStore();
const votings = ref([]);
const voting = ref({});

const votingStatusCode = computed(() => votingStore.response.status);
const votingErrorList = computed(() => votingStore.response?.error || {});
const votingErrorMessage = computed(() => votingStore.response?.message || "");

const userVotingStore = useUserVotingStore();

const memoStore = useMemoStore();

const date = ref('');
const choosedDate = ref('');
const isActivityFormOpen = ref(false);
const activityFormTitle = ref("New");

const isMemoFormOpen = ref(false);
const memoFormTitle = ref("Create");
const choosedMemoId = ref("");

const isVotingFormOpen = ref(false);
const isVotingOpen = ref(false);
const isVotingResultOpen = ref(false);
const votingFormTitle = ref("Create");
const votingOptions = ref([
  {
    option: 'Opsi 1',
    total: 0,
    is_added: true
  },
  {
    option: 'Opsi 2',
    total: 0,
    is_added: true
  }
]);

activityStore.userId = userId;

// Form

const activityForm = reactive({
  id: "",
  group_id: "",
  user_id: user.id,
  description: "",
  start_time: "",
  end_time: "",
  is_priority: 0,
  is_finished: 0,
  google_calendar_event_id: "",
});

const memoForm = reactive({
  id: "",
  group_id: groupId.value,
  group_name: "",
  message: "",
  date: new Date().toISOString().slice(0, 10),
  time: ""
});

const votingForm = reactive({
  id: "",
  group_id: "",
  description: "",
  limit_time: "",
  voting_options: votingOptions.value,
  voting_options_deleted: [],
});

// const validateEndTime = () => {
//   if (activityForm.start_time && activityForm.end_time) {
//     endTimeError.value =
//       activityForm.end_time < activityForm.start_time;
//   } else {
//     endTimeError.value = false;
//   }
// };

// watch(
//   () => activityForm.end_time,
//   validateEndTime
// );

// Open Modal

const openActivityFormModal = async (activity) => {
  if (!groupId.value || isAdmin.value) {
    isActivityFormOpen.value = true;
    if (activity != 'add') {

      activityForm.id = activity.id;
      activityForm.group_id = activity.group_id;
      activityForm.user_id = activity.user_id;
      activityForm.description = activity.description;
      activityForm.start_time = activity.start_time.substr(11, 5);
      activityForm.end_time = activity.end_time.substr(11, 5);
      activityForm.is_priority = activity.is_priority;
      activityForm.is_finished = activity.is_finished;
      activityForm.google_calendar_event_id = activity.google_calendar_event_id;

      activityFormTitle.value = 'Update';
    } else {
      activityForm.id = '';

      if (groupId.value) {
        activityForm.group_id = groupId.value;
        activityForm.user_id = userId;
      } else {
        activityForm.group_id = '';
        activityForm.user_id = userId;
      }
      activityForm.description = '';
      activityForm.start_time = '';
      activityForm.end_time = '';
      activityForm.is_priority = 0;
      activityForm.is_finished = 0;
      activityForm.google_calendar_event_id = '';

      activityFormTitle.value = 'New';
    }
  }
}

const openMemoFormModal = async (memo) => {
  if (isAdmin.value) {
    isMemoFormOpen.value = true;
    if (memo != 'add') {
      memoForm.id = memo.memoId;
      memoForm.group_id = memo.groupId;
      memoForm.group_name = memo.groupName;
      memoForm.message = memo.message;
      choosedMemoId.value = memo.id;
      memoFormTitle.value = 'Update';
    } else {
      memoForm.id = "";
      memoForm.group_id = groupId.value;
      memoForm.group_name = "";
      memoForm.message = "";
      choosedMemoId.value = "";
      memoFormTitle.value = 'Create';
    }
  }
}

const openVotingFormModal = async (votingData) => {
  isVotingFormOpen.value = true;

  if (votingData != 'add') {
    votingForm.id = votingData.id;
    votingForm.group_id = votingData.groupId;
    votingForm.description = votingData.description;
    votingForm.limit_time = votingData.limitTime.substr(11, 5);
    votingForm.voting_options = votingData.votingOptions.map(option => ({
      ...option,
      is_updated: false
    }));
    votingFormTitle.value = 'Update';
  } else {
    if (groupId.value) {
      votingForm.group_id = groupId.value;
    }

    votingForm.id = "";
    votingForm.description = "";
    votingForm.limit_time = "";
    votingForm.voting_options = votingOptions.value;
    votingFormTitle.value = 'Create';
  }
}

const openVotingModal = async (votingData) => {
  voting.value = votingData;

  checkHasVoted(votingData.userVotings);

  votingForm.id = votingData.id;
  votingForm.group_id = votingData.groupId;
  votingForm.description = votingData.description;
  votingForm.limit_time = votingData.limitTime.substr(11, 5);
  votingForm.voting_options = votingData.votingOptions;

  isVotingOpen.value = true;
  isVotingResultOpen.value = false;
}

const openVotingResult = async () => {
  startProgress();

  voting.value = await votingStore.getVotingById(voting.value.votingOptions[0].votingId);

  chartData.value = setChartData(voting.value.votingOptions);
  chartOptions.value = setChartOptions();

  isVotingOpen.value = false;
  isVotingResultOpen.value = true;
  finishProgress();
}

// Activity

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
  choosedDate.value = date.value;
  activityStore.startTime = choosedDate.value;
  activityStore.endTime = choosedDate.value;
  votingStore.limitTime = choosedDate.value;

  await getActivities();

  if (groupId.value) {
    startProgress();
    await getVotings();
    finishProgress();
  }
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
    activityForm.start_time = getFormattedTime(choosedDate.value, activityForm.start_time);
    activityForm.end_time = getFormattedTime(choosedDate.value, activityForm.end_time);
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
    activityForm.google_calendar_event_id = activity.google_calendar_event_id;
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
      showErrorToast("Delete activity failed");
    }
  }
}

const getCurrentTime = () => {
  const now = new Date();

  const hours = now.getHours();
  const minutes = now.getMinutes();
  const seconds = now.getSeconds();

  const currentTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

  return currentTime;
}

// Voting

const getVotings = async () => {
  await votingStore.getVotings();

  if (votingStore.votings) {
    votings.value = votingStore.votings;
  }
}

const addOption = () => {
  votingForm.voting_options.push({
    option: 'Opsi lain',
    total: 0,
    is_added: true
  })
}

const deleteOption = (id, index) => {
  votingForm.voting_options.splice(index, 1);
  if (id != 'default') {
    votingForm.voting_options_deleted.push({
      id
    })
  }
}

const markAsUpdated = (index) => {
  if (votingFormTitle.value == 'Update') {
    if (!votingForm.voting_options[index].is_added) {
      votingForm.voting_options[index].is_updated = true;
    }
  }
};

const saveVoting = async () => {
  startProgress();
  try {
    votingForm.limit_time = getFormattedTime(choosedDate.value, votingForm.limit_time);

    if (votingForm.id) {
      await votingStore.updateVoting(votingForm);
      if (votingStatusCode.value != 200) {
        failProgress();
        showErrorToast("Failed to update voting", votingErrorMessage);
      } else {
        isVotingFormOpen.value = false;
        finishProgress();
        showSuccessToast("Voting updated successfully!");
      }
    } else {
      await votingStore.addVotings(votingForm);
      if (votingStatusCode.value != 200) {
        failProgress();
        showErrorToast("Failed to add voting", votingErrorMessage);
      } else {
        isVotingFormOpen.value = false;

        finishProgress();
        showSuccessToast("Voting added successfully!");
      }
    }
    await getVotings();

  } catch (error) {
    console.error(error);
    failProgress();
  }
}

const deleteVoting = async (votingId) => {
  const confirmed = await showDeleteConfirmationDialog();

  if (confirmed) {
    startProgress();
    try {
      await votingStore.deleteVoting(votingId);
      await getVotings();

      finishProgress();
      showSuccessToast("Delete voting successfully");
    } catch (error) {
      console.error(error);
      failProgress();
      showErrorToast("Delete voting failed");
    }
  }
}

const chooseOption = async (optionId, votingId) => {
  selectedOption.value = optionId;
  try {
    await votingStore.chooseOption(optionId);

    hasVoted.value = true;

    const userVotingForm = {
      user_id: userId,
      voting_id: votingId
    }
    await userVotingStore.addUserVotings(userVotingForm);
  } catch (error) {
    console.error(error);
  }
}

const checkHasVoted = (userVotings) => {

  const userVote = userVotings.some(vote => vote.userId === userId);

  if (userVote) {
    hasVoted.value = true;
  } else {
    hasVoted.value = false;
  }
}

// Firebase
import {
  getFirestore,
  onSnapshot,
  collection,
  doc,
  deleteDoc,
  setDoc,
  addDoc,
  query,
  where
} from 'firebase/firestore';
// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

const memos = ref([]);

const saveMemo = async () => {
  memoForm.time = getCurrentTime();

  const group = await groupStore.getGroupById(memoForm.group_id);
  memoForm.group_name = group.name;

  if (memoFormTitle.value == 'Create') {
    await addMemo();
  } else {
    await updateMemo(choosedMemoId.value);
  }
}

const addMemo = async () => {
  startProgress();
  try {
    const memo = await memoStore.addMemos(memoForm);
    memoForm.id = memo.id;

    addDoc(collection(db, 'memo'), memoForm);
    isMemoFormOpen.value = false;
    finishProgress();
  } catch (error) {
    console.error(error);
    failProgress();
  }
}

const updateMemo = async (memoId) => {
  try {
    setDoc(doc(db, 'memo', memoId), memoForm)
    isMemoFormOpen.value = false;

    await memoStore.updateMemo(memoForm);
  } catch (error) {
    console.error(error);
  }
}

const deleteMemo = async (id, memoId) => {
  const confirmed = await showDeleteConfirmationDialog();

  if (confirmed) {
    try {
      deleteDoc(doc(db, 'memo', id));

      await memoStore.deleteMemo(memoId);
    } catch (error) {
      console.error(error);
    }
  }
}

//Firebase
const latestQuery = ref(query(collection(db, "memo")));

let unsubscribe = null;

const getMemos = () => {
  if (unsubscribe) {
    unsubscribe();
  }

  if (groupId.value) {
    latestQuery.value = query(collection(db, "memo"), where('group_id', '==', groupId.value));
  } else {
    latestQuery.value = query(collection(db, "memo"))
  }

  unsubscribe = onSnapshot(latestQuery.value, (snapshot) => {
    memos.value = snapshot.docs.map((doc) => {
      return {
        id: doc.id,
        memoId: doc.data().id,
        groupId: doc.data().group_id,
        groupName: doc.data().group_name,
        message: doc.data().message,
        time: doc.data().time,
        date: doc.data().date,
      };
    });
  });
};

// Chart

const chartData = ref();
const chartOptions = ref();
const selectedOption = ref(null);

const setChartData = (votingOptions) => {
  return {
    labels: votingOptions.map(option => option.option),
    datasets: [
      {
        label: 'Voting Result',
        data: votingOptions.map(option => option.total),
        backgroundColor: '#00ADB5',
        borderColor: '#067e82',
        borderWidth: 1
      }
    ]
  };
};

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue('--p-text-color');
  const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
  const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

  return {
    plugins: {
      legend: {
        labels: {
          color: textColor
        }
      }
    },
    scales: {
      x: {
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      },
      y: {
        beginAtZero: true,
        ticks: {
          color: textColorSecondary
        },
        grid: {
          color: surfaceBorder
        }
      }
    }
  };
}

onUnmounted(() => {
  if (unsubscribe) {
    unsubscribe();
  }
});

onMounted(async () => {
  const today = new Date();
  const formattedToday = getFormattedDate(today);

  date.value = formattedToday;
  choosedDate.value = date.value;
  activityStore.startTime = date.value;
  activityStore.endTime = date.value;
  votingStore.limitTime = date.value;

  if (!groupId.value) {
    groupId.value = '';
    activityStore.userId = userId;
  } else {
    activityStore.userId = '';
    isAdmin.value = await checkIsAdminById(groupId.value);
  }

  activityStore.groupId = groupId.value;
  votingStore.groupId = groupId.value;

  await getActivities();

  if (groupId.value) {
    startProgress();
    await getVotings();
    getMemos();
    finishProgress();
  }
});

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

.default-option {
  background-color: white;
  color: black;
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

.selected-option {
  background-color: #067e82;
  color: white;
}

.votting-button {
  color: white;
  background-color: #00ADB5;
  box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.25);
}
</style>