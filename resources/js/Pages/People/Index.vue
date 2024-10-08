<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="People" />
  <AuthenticatedLayout>
    <div class="mb-5">
      <h5 class="text-h5 font-weight-bold">People</h5>
      <Breadcrumbs :items="breadcrumbs" class="pa-0 mt-1" />
    </div>
    <v-card class="pa-4">
      <div class="d-flex flex-wrap align-center">
        <v-text-field
          v-model="search"
          label="Search"
          variant="underlined"
          prepend-inner-icon="mdi-magnify"
          hide-details
          clearable
          single-line
        />
        <v-spacer />
        <Link href="/people/create" as="div">
          <v-btn color="primary">Create</v-btn>
        </Link>
      </div>
      <v-data-table-server
        v-model:options="options"
        :items="items"
        :items-length="totalItems"
        :headers="headers"
        :search="search"
        class="elevation-0"
        :loading="isLoadingTable"
        @update:options="loadItems"
      >
        <template #[`item.gender`]="{ item }">{{ item.gender == 'male' ? 'Male' : 'Female' }}</template>
        <template #[`item.action`]="{ item }">
          <v-icon class="ma-2" color="primary" icon="mdi-eye" size="small" @click="showItem(item)" />
          <Link :href="`/people/${item.id}/edit`" as="button">
            <v-icon color="warning" icon="mdi-pencil" size="small" />
          </Link>
          <v-icon class="ml-2" color="error" icon="mdi-delete" size="small" @click="deleteItem(item)" />
        </template>
      </v-data-table-server>
    </v-card>
    <v-row justify="center">
      <v-dialog v-model="deleteDialog" persistent width="auto">
        <v-card>
          <v-card-text>Are you sure you want to delete this item?</v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn color="error" text @click="deleteDialog = false">Cancel</v-btn>
            <v-btn color="primary" :loading="isLoading" text @click="submitDelete">Delete</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </AuthenticatedLayout>
</template>

<script>
export default {
  name: 'PeopleIndex',
  data() {
    return {
      headers: [
        { title: 'Name', key: 'name' },
        { title: 'Gender', key: 'gender' },
        { title: 'Email', key: 'email' },
        { title: 'Phone Number', key: 'phone' },
        { title: 'Created At', key: 'created_at' },
        { title: 'Action', key: 'action', sortable: false },
      ],
      items: [],
      totalItems: 0,
      breadcrumbs: [
        {
          title: 'Dashboard',
          disabled: false,
          href: '/dashboard',
        },
        {
          title: 'People',
          disabled: true,
        },
      ],
      isLoadingTable: false,
      options: {},
      search: null,
      deleteDialog: false,
      isLoading: false,
      deleteId: null,
    }
  },
  methods: {
    loadItems() {
      this.isLoadingTable = true
      const { page, itemsPerPage, sortBy, search } = this.options
      var params = {
        page: page,
        limit: itemsPerPage,
        sort: sortBy[0],
      }
      if (search) {
        params.search = search
      }
      this.$axios
        .get('/people', { params })
        .then((response) => {
          this.items = response.data.data
          this.totalItems = response.data.total
          this.isLoadingTable = false
        })
        .catch(() => {
          this.$toast.error('Failed to load data')
          this.isLoadingTable = false
        })
    },
    showItem(item) {
      this.$inertia.visit(`/people/${item.id}`)
    },
    deleteItem(item) {
      this.deleteId = item.id
      this.deleteDialog = true
    },
    submitDelete() {
      this.isLoading = true
      this.$inertia.delete(`/people/${this.deleteId}`, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          this.isLoading = false
          this.deleteDialog = false
          this.loadItems()
        },
      })
    },
  },
}
</script>
