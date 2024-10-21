<template>
  <Layout>
    <div class="d-flex">
      <div class="m-3 left-card">
        <div class="card main-bg p-3">
          <h6 class="font-4 mb-3">Calendar</h6>
          <DatePicker v-model="date" inline showWeek class="w-full sm:w-[30rem]" />
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
            <i class="bx bx-plus memo-bold-font" style="color: #EEEEEE; font-size: 16px;"></i>
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

          <!-- <div class="card bg-white py-2 px-3 d-flex flex-row align-items-center">
            <div class="d-flex flex-column align-items-center activity-time">
              <p class="mb-0">08:40</p>
              <p class="mb-0">-</p>
              <p class="mb-0">09:20</p>
            </div>
            <div>
              <p class="activity-description mb-0">Deskripsi aktivitas</p>
            </div>
            <div class="form-check">
              <input class="form-check-input activity-check" type="checkbox" value="" id="flexCheckChecked">
            </div>
            <div>
              <i class="bx bx-dots-vertical-rounded mt-2 memo-bold-font" style="font-size: 16px;"></i>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from "../../layouts/main";
import DatePicker from 'primevue/datepicker';
import { ref } from "vue";

const date = ref();

</script>

<style scoped>
.activity-check {
  width: 24px;
  height: 24px;
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