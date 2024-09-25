<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Breadcrumbs from '@/Components/Breadcrumbs.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
  person: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <Head title="Edit People" />
  <AuthenticatedLayout>
    <div class="mb-5">
      <h5 class="text-h5 font-weight-bold">{{ props.person.nome }}</h5>
      <Breadcrumbs :items="breadcrumbs" class="pa-0 mt-1" />
    </div>
  </AuthenticatedLayout>
</template>

<script>
export default {
  name: 'PeopleEdit',
  data() {
    return {
      genders: [
        { text: 'Male', value: 'male' },
        { text: 'Female', value: 'female' },
      ],
      breadcrumbs: [
        {
          title: 'Dashboard',
          disabled: false,
          href: '/dashboard',
        },
        {
          title: 'People',
          disabled: false,
          href: '/people',
        },
        {
          title: 'Edit',
          disabled: true,
        },
      ],
    }
  },
}
</script>
