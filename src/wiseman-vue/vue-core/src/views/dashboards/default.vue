<template>
  <Layout>
    <div class="d-flex">
      <div class="m-3 left-card">
        <div class="card main-bg p-3">
          <h6 class="font-4 mb-3">Calendar</h6>
          <DatePicker v-model="date" inline class="w-full sm:w-[30rem]" />
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
              @click="openGroupFormModal('add')"></i>
          </div>
          <table class="table bg-white align-middle" style="border-radius: 4px;">
            <tr>
              <td style="text-align: center; width: 80px;">
                <div class="d-flex flex-column align-items-center activity-time">
                  <p class="mb-0">08:40</p>
                  <p class="mb-0">-</p>
                  <p class="mb-0">09:20</p>
                </div>
              </td>
              <td style="width: 100%;">
                <p class="activity-description mb-0">Deskripsi aktivitas</p>
              </td>
              <td style="text-align: center; width: 50px;">
                <input class="form-check-input activity-check" type="checkbox" value="" id="flexCheckChecked">
              </td>
              <td style="text-align: center; width: 40px;">
                <i class="bx bx-dots-vertical-rounded memo-bold-font mt-1" style="font-size: 16px;"></i>
              </td>
            </tr>
          </table>
        </div>

        <!-- ========== Activity Modal ========== -->
        <BModal v-model="isActivityFormOpen" id="modal-standard" :title="activityFormTitle + ' Activity'"
          title-class="font-18" :ok-title="activityFormTitle" @ok="saveGroup" @hide.prevent
          @cancel="isActivityFormOpen = false" @close="isActivityFormOpen = false">
          <BRow>
            <BCol cols="12" class="mt-2">
              <BForm class="form-horizontal" role="form">
                <BRow class="mb-3">
                  <BCol>
                    <textarea class="form-control" :class="{
                      'is-invalid': !!(groupErrorList && groupErrorList.description),
                    }" id="form-description" type="text" placeholder="Masukkan deskripsi aktivitas ..."
                      v-model="activityForm.description" />

                    <template v-if="!!(groupErrorList && groupErrorList.description)">
                      <div class="invalid-feedback" v-for="(err, index) in groupErrorList.description" :key="index">
                        <span>{{ err }}</span>
                      </div>
                    </template>
                  </BCol>
                </BRow>
                <Brow class="d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center" >
                    <BCol md="5">
                      <input class="form-control" :class="{
                        'is-invalid': !!(userErrorList && userErrorList.start_time),
                      }" id="form-start-activity" placeholder="Start time" v-model="activityForm.start_time" type="time" />
                      <b-form-timepicker id="timepicker-sm" size="sm" locale="en" class="mb-2"></b-form-timepicker>
                      <template v-if="!!(userErrorList && userErrorList.start_time)">
                        <div class="invalid-feedback" v-for="(err, index) in userErrorList.start_time" :key="index">
                          <span>{{ err }}</span>
                        </div>
                      </template>
                    </BCol>
                    <BCol md="0">
                      <p class="activity-time mb-0 mx-2">-</p>
                    </BCol>
                    <BCol md="5">
                      <input class="form-control" :class="{
                        'is-invalid': !!(userErrorList && userErrorList.end_time),
                      }" id="form-end-activity" placeholder="End ime" v-model="activityForm.end_time" type="time" />
                      <template v-if="!!(userErrorList && userErrorList.end_time)">
                        <div class="invalid-feedback" v-for="(err, index) in userErrorList.end_time" :key="index">
                          <span>{{ err }}</span>
                        </div>
                      </template>
                    </BCol>
                  </div>
                  <BCol md="2">
                    <input class="form-check-input activity-check-form" type="checkbox" value="" id="flexCheckChecked">
                    <label class="form-check-label mt-1 ms-1" for="flexCheckDefault">
                      Prioritas
                    </label>
                  </BCol>
                </Brow>
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
import { ref, reactive } from "vue";

const date = ref();
const isActivityFormOpen = ref(false);
const activityFormTitle = ref("Create");

const activityForm = reactive({
  id: "",
  group_id: "",
  user_id: "",
  name: "",
  description: "",
  start_time: "",
  end_time: "",
  is_priority: "",
  is_finished: "",
});

const openGroupFormModal = async (groupId) => {
  isActivityFormOpen.value = true;
  if (groupId != 'add') {
    activityFormTitle.value = 'Update';
  } else {
    activityFormTitle.value = 'Create';
  }
}

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