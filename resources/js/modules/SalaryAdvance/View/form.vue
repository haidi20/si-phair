<template>
  <div>
    <b-modal
      id="salary_advance_form"
      ref="salary_advance_form"
      :title="getTitleForm"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label="Karyawan" label-for="employee_id" class>
            <VueSelect
              id="employee_id"
              class="cursor-pointer"
              v-model="form.employee_id"
              placeholder="Pilih Karyawan"
              :options="getOptionEmployees"
              :reduce="(data) => data.id"
              label="name_and_position"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" md="6">
          <b-form-group label="Jumlah kasbon" label-for="loan_amount" class>
            <b-form-input v-model="loan_amount" id="loan_amount" name="loan_amount"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="6">
          <b-form-group label="Alasan" label-for="reason" class>
            <b-form-input v-model="form.reason" id="reason" name="reason"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <template v-if="getCan('metode pembayaran kasbon')">
        <b-row>
          <b-col cols="6">
            <b-form-group label="Nominal Potongan Per Bulan" label-for="monthly_deduction" class>
              <b-form-input
                v-model="monthly_deduction"
                id="monthly_deduction"
                name="monthly_deduction"
                autocomplete="off"
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="6">
            <b-form-group label="Metode Pembayaran" label-for="payment_method" class>
              <VueSelect
                id="payment_method"
                class="cursor-pointer"
                v-model="form.payment_method"
                :options="getOptionPaymentMethods"
                :reduce="(data) => data.id"
                label="name"
                searchable
                style="min-width: 180px"
              />
            </b-form-group>
          </b-col>
        </b-row>
      </template>
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button
            style="float: right"
            variant="success"
            @click="onSend()"
            :disabled="is_loading"
          >Simpan</b-button>
          <span v-if="is_loading" style="float: right">Loading...</span>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script src="../Script/form.js"></script>

<style lang="scss" scoped>
</style>
