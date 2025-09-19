<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1>All Form Submissions</h1>
        <p class="lead">View submissions from all forms</p>
      </div>
      <router-link to="/forms" class="btn btn-outline-primary">Back to Forms</router-link>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Search submissions..." v-model="searchQuery">
            </div>
            
            <div v-if="loading" class="text-center">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>

            <div v-else>
              <div v-if="submissions.length === 0" class="text-center py-4">
                <p>No submissions found.</p>
              </div>
              
              <div v-else>
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Submission ID</th>
                        <th>Form Name</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="submission in filteredSubmissions" :key="submission.id" 
                          @click="selectSubmission(submission)"
                          :class="{ 'table-active': selectedSubmission && selectedSubmission.id === submission.id }"
                          style="cursor: pointer;">
                        <td>#{{ submission.id }}</td>
                        <td>{{ getFormTitle(submission.form_id) }}</td>
                        <td>{{ formatDate(submission.created_at) }}</td>
                        <td>
                          <button class="btn btn-sm btn-outline-primary" 
                                  @click.stop="viewSubmissionDetails(submission)">
                            View Details
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

   
    <div v-if="selectedSubmission" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Submission Details - #{{ selectedSubmission.id }}</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-4">
              <div class="col-md-6">
                <h6>Form Information</h6>
                <p><strong>Form Name:</strong> {{ getFormTitle(selectedSubmission.form_id) }}</p>
                <p><strong>Submission ID:</strong> #{{ selectedSubmission.id }}</p>
              </div>
              <div class="col-md-6">
                <h6>Submission Details</h6>
                <p><strong>Submitted At:</strong> {{ formatDate(selectedSubmission.created_at) }}</p>
                <p><strong>Total Fields:</strong> {{ selectedSubmission.answers.length }}</p>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h6 class="mb-0">Form Responses</h6>
              </div>
              <div class="card-body">
                <div v-for="answer in selectedSubmission.answers" :key="answer.id" class="mb-3">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>
                      <strong>{{ answer.field?.label || 'Unnamed Field' }}</strong>
                      <span v-if="answer.field?.required" class="text-danger">*</span>
                    </div>
                    <small class="text-muted">{{ answer.field?.type || 'unknown' }}</small>
                  </div>
                  <div class="mt-1 p-2 bg-light rounded">
                    {{ answer.value || 'No response' }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
            <router-link :to="`/submissions/form/${selectedSubmission.form_id}`" 
                        class="btn btn-primary">
              View All Submissions for This Form
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      submissions: [],
      forms: [],          
      loading: true,
      searchQuery: '',
      selectedSubmission: null
    };
  },
  computed: {
    filteredSubmissions() {
      if (!this.searchQuery) return this.submissions;

      const query = this.searchQuery.toLowerCase();
      return this.submissions.filter(submission => {
        const formTitle = this.getFormTitle(submission.form_id).toLowerCase();
        return (
          submission.id.toString().includes(query) ||
          formTitle.includes(query) ||
          submission.answers.some(answer => 
            (answer.value && answer.value.toLowerCase().includes(query)) ||
            (answer.field?.label && answer.field.label.toLowerCase().includes(query))
          )
        );
      });
    }
  },
  async mounted() {
    await this.fetchAllForms();
    await this.fetchAllSubmissions();
  },
  methods: {
    async fetchAllSubmissions() {
      try {
        this.loading = true;
        const response = await axios.get('/api/submissions');
        this.submissions = response.data;
      } catch (error) {
        console.error('Error fetching submissions:', error);
      } finally {
        this.loading = false;
      }
    },
    async fetchAllForms() {
      try {
        const response = await axios.get('/api/forms'); 
        this.forms = response.data;
      } catch (error) {
        console.error('Error fetching forms:', error);
      }
    },
    getFormTitle(formId) {
      const form = this.forms.find(f => f.id === formId);
      return form ? form.title : `Form #${formId}`;
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleString();
    },
    selectSubmission(submission) {
      this.selectedSubmission = submission;
    },
    viewSubmissionDetails(submission) {
      this.selectedSubmission = submission;
    },
    closeModal() {
      this.selectedSubmission = null;
    }
  }
};
</script>

<style scoped>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
}

.modal-content {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.bg-light {
  background-color: #f8f9fa !important;
}
</style>
