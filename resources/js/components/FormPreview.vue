<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>{{ form.title }}</h1>
      <div>
        <router-link :to="`/edit/${form.id}`" class="btn btn-outline-primary me-2">Edit</router-link>
        <router-link :to="`/submissions/${form.id}`" class="btn btn-outline-info">View Submissions</router-link>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <form @submit.prevent="submitForm">
          <div v-for="field in form.fields" :key="field.id" class="mb-3">
            <label :for="`field-${field.id}`" class="form-label">
              {{ field.label }} <span v-if="field.required" class="text-danger">*</span>
            </label>
            
            <input v-if="field.type === 'text'" 
                   :id="`field-${field.id}`" 
                   type="text" 
                   class="form-control" 
                   v-model="answers[field.id]"
                   :required="field.required">
            
            <textarea v-else-if="field.type === 'textarea'" 
                      :id="`field-${field.id}`" 
                      class="form-control" 
                      v-model="answers[field.id]"
                      :required="field.required"></textarea>
            
            <div v-else-if="field.type === 'radio'">
              <div v-for="(option, index) in field.options" :key="index" class="form-check">
                <input :id="`field-${field.id}-${index}`" 
                       type="radio" 
                       class="form-check-input" 
                       :name="`field-${field.id}`" 
                       :value="option" 
                       v-model="answers[field.id]"
                       :required="field.required">
                <label class="form-check-label" :for="`field-${field.id}-${index}`">{{ option }}</label>
              </div>
            </div>
            
            <div v-else-if="field.type === 'checkbox'">
              <div v-for="(option, index) in field.options" :key="index" class="form-check">
                <input :id="`field-${field.id}-${index}`" 
                       type="checkbox" 
                       class="form-check-input" 
                       :value="option" 
                       v-model="answers[field.id]"
                       :required="field.required && (!answers[field.id] || answers[field.id].length === 0)">
                <label class="form-check-label" :for="`field-${field.id}-${index}`">{{ option }}</label>
              </div>
            </div>
          </div>
          
          <button type="submit" class="btn btn-primary">Submit Form</button>
        </form>
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
        title: '',
        fields: []
      },
      answers: {},
      submitted: false
    };
  },
  async mounted() {
    await this.fetchForm();
  },
  methods: {
    async fetchForm() {
      try {
        const response = await axios.get(`/api/forms/${this.id}`);
        this.form = response.data;
        
        // Initialize answers object
        this.form.fields.forEach(field => {
          if (field.type === 'checkbox') {
            this.answers[field.id] = [];
          } else {
            this.answers[field.id] = '';
          }
        });
      } catch (error) {
        console.error('Error fetching form:', error);
      }
    },
    async submitForm() {
      try {
        await axios.post(`/api/forms/${this.id}/submissions`, {
          answers: this.answers
        });
        
        this.submitted = true;
        alert('Form submitted successfully!');
        this.answers = {};
        
        // Re-initialize answers
        this.form.fields.forEach(field => {
          if (field.type === 'checkbox') {
            this.answers[field.id] = [];
          } else {
            this.answers[field.id] = '';
          }
        });
      } catch (error) {
        if (error.response && error.response.status === 422) {
          alert('Please fill in all required fields.');
        } else {
          console.error('Error submitting form:', error);
          alert('Error submitting form. Please try again.');
        }
      }
    }
  }
};
</script>