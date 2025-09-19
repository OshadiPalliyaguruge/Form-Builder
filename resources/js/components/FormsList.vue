<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1>Form Builder</h1>
        <p class="lead">Manage your forms</p>
      </div>
      <router-link to="/create" class="btn btn-primary">Create New Form</router-link>
    </div>
    
    <div class="row">
      <div class="col-md-3">
        <div class="list-group">
          <router-link to="/" class="list-group-item list-group-item-action">Dashboard</router-link>
          <router-link to="/forms" class="list-group-item list-group-item-action active">Forms</router-link>
          <a href="#" class="list-group-item list-group-item-action">Submissions</a>
          <a href="#" class="list-group-item list-group-item-action">Settings</a>
        </div>
      </div>
      
      <div class="col-md-9">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Forms</h5>
            <p class="text-muted mb-0">Manage your forms and view submissions</p>
          </div>
          <div class="card-body">
            <div v-if="loading" class="text-center">
              <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
            
            <div v-else>
              <div v-if="forms.length === 0" class="text-center py-4">
                <p>No forms found. Create your first form!</p>
                <router-link to="/create" class="btn btn-primary">Create Form</router-link>
              </div>
              
              <div v-else>
                <div v-for="form in forms" :key="form.id" class="card mb-3">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">{{ form.title }}</h5>
                      <div>
                        <router-link :to="`/edit/${form.id}`" class="btn btn-sm btn-outline-primary me-2">Edit</router-link>
                        <router-link :to="`/preview/${form.id}`" class="btn btn-sm btn-outline-secondary me-2">Preview</router-link>
                        <router-link :to="`/submissions/${form.id}`" class="btn btn-sm btn-outline-info me-2">
                          {{ form.submissions_count || 0 }}
                        </router-link>
                        <button @click="deleteForm(form.id)" class="btn btn-sm btn-outline-danger">Delete</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      forms: [],
      loading: true
    };
  },
  async mounted() {
    await this.fetchForms();
  },
  methods: {
    async fetchForms() {
      try {
        this.loading = true;
        const response = await axios.get('/api/forms');
        this.forms = response.data;
        this.loading = false;
      } catch (error) {
        console.error('Error fetching forms:', error);
        this.loading = false;
      }
    },
    async deleteForm(id) {
      if (confirm('Are you sure you want to delete this form?')) {
        try {
          await axios.delete(`/api/forms/${id}`);
          this.forms = this.forms.filter(form => form.id !== id);
        } catch (error) {
          console.error('Error deleting form:', error);
        }
      }
    }
  }
};
</script>