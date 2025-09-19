<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Form Submissions</h1>
      <router-link :to="`/edit/${form.id}`" class="btn btn-outline-primary">Edit Form</router-link>
    </div>
    
    <h2>{{ form.title }} · {{ submissions.length }} submissions</h2>
    
    <div class="card">
      <div class="card-body">
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Search submissions..." v-model="searchQuery">
        </div>
        
        <div class="list-group">
          <div v-for="submission in filteredSubmissions" :key="submission.id" 
               class="list-group-item list-group-item-action" 
               @click="selectSubmission(submission)"
               :class="{ active: selectedSubmission && selectedSubmission.id === submission.id }">
            <div class="d-flex justify-content-between">
              <h6 class="mb-1">Submission #{{ submission.id }}</h6>
              <small>{{ formatDate(submission.created_at) }}</small>
            </div>
            <div v-for="answer in submission.answers.slice(0, 2)" :key="answer.id" class="text-muted">
              {{ answer.field.label }}: {{ answer.value }}
            </div>
            <small v-if="submission.answers.length > 2" class="text-muted">
              +{{ submission.answers.length - 2 }} more fields
            </small>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selectedSubmission" class="card mt-4">
      <div class="card-header">
        <h5 class="mb-0">Submission Details</h5>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <strong>Submitted</strong><br>
            {{ formatDate(selectedSubmission.created_at) }}
          </div>
        </div>
        
        <h6>Form Data</h6>
        <div v-for="answer in selectedSubmission.answers" :key="answer.id" class="mb-3">
          <strong>{{ answer.field.label }}</strong><br>
          {{ answer.value }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: String
  },
  data() {
    return {
      form: {
        title: ''
      },
      submissions: [],
      selectedSubmission: null,
      searchQuery: ''
    };
  },
  computed: {
    filteredSubmissions() {
      if (!this.searchQuery) return this.submissions;
      
      return this.submissions.filter(submission => {
        return submission.answers.some(answer => 
          answer.value.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      });
    }
  },
  async mounted() {
    await this.fetchForm();
    await this.fetchSubmissions();
  },
  methods: {
    async fetchForm() {
      try {
        const response = await axios.get(`/api/forms/${this.id}`);
        this.form = response.data;
      } catch (error) {
        console.error('Error fetching form:', error);
      }
    },
    async fetchSubmissions() {
      try {
        const response = await axios.get(`/api/forms/${this.id}/submissions`);
        this.submissions = response.data;
      } catch (error) {
        console.error('Error fetching submissions:', error);
      }
    },
    selectSubmission(submission) {
      this.selectedSubmission = submission;
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleString();
    }
  }
};
</script>