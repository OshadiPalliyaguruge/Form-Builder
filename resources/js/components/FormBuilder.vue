<template>
  <div>
    <h1>{{ isEditing ? 'Edit Form' : 'Create Form' }}</h1>
    
    <div class="row">
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="form-group mb-3">
              <label for="formTitle">Form Title *</label>
              <input type="text" class="form-control" id="formTitle" v-model="formData.title" placeholder="Enter form title">
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Form Fields</h5>
            <div>
              <button v-for="type in fieldTypes" :key="type" @click="addField(type)" class="btn btn-sm btn-outline-primary me-1">
                + {{ typeLabel(type) }}
              </button>
            </div>
          </div>
          <div class="card-body">
            <div v-for="(field, index) in formData.fields" :key="index" class="field-item mb-3 p-3 border rounded">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">{{ typeLabel(field.type) }}</h6>
                <div>
                  <button @click="moveFieldUp(index)" class="btn btn-sm btn-outline-secondary me-1" :disabled="index === 0">↑</button>
                  <button @click="moveFieldDown(index)" class="btn btn-sm btn-outline-secondary me-1" :disabled="index === formData.fields.length - 1">↓</button>
                  <button @click="removeField(index)" class="btn btn-sm btn-danger">×</button>
                </div>
              </div>

              <div class="form-group mb-2">
                <label>Label</label>
                <input type="text" class="form-control" v-model="field.label" :placeholder="`${typeLabel(field.type)} Field`">
              </div>

              <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" v-model="field.required" :id="`required-${index}`">
                <label class="form-check-label" :for="`required-${index}`">Required field</label>
              </div>

              <div v-if="field.type === 'checkbox' || field.type === 'radio'" class="options-section">
                <label>Options</label>
                <div v-for="(option, optIndex) in field.options" :key="optIndex" class="d-flex mb-2">
                  <input type="text" class="form-control me-2" v-model="field.options[optIndex]" placeholder="Option text">
                  <button @click="removeOption(field, optIndex)" class="btn btn-sm btn-danger">×</button>
                </div>
                <button @click="addOption(field)" class="btn btn-sm btn-outline-secondary">Add Option</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <button @click="saveForm" class="btn btn-primary w-100 mb-2">Save Form</button>
            <router-link v-if="isEditing" :to="`/preview/${formId}`" class="btn btn-outline-secondary w-100">Preview Form</router-link>
          </div>
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
      formData: {
        title: '',
        fields: []
      },
      fieldTypes: ['text', 'textarea', 'checkbox', 'radio']
    };
  },
  computed: {
    isEditing() {
      return !!this.id;
    },
    formId() {
      return this.id;
    }
  },
  async mounted() {
    if (this.isEditing) {
      await this.fetchForm();
    }
  },
  methods: {
    typeLabel(type) {
      const labels = {
        text: 'Text Input',
        textarea: 'Text Area',
        checkbox: 'Checkbox',
        radio: 'Radio Button'
      };
      return labels[type] || type;
    },
    async fetchForm() {
      try {
        const response = await axios.get(`/api/forms/${this.id}`);
        this.formData = response.data;
      } catch (error) {
        console.error('Error fetching form:', error);
      }
    },
    addField(type) {
      const newField = {
        label: '',
        type: type,
        required: false,
        options: type === 'checkbox' || type === 'radio' ? ['Option 1', 'Option 2'] : null
      };
      this.formData.fields.push(newField);
    },
    removeField(index) {
      this.formData.fields.splice(index, 1);
    },
    moveFieldUp(index) {
      if (index > 0) {
        const field = this.formData.fields[index];
        this.formData.fields.splice(index, 1);
        this.formData.fields.splice(index - 1, 0, field);
      }
    },
    moveFieldDown(index) {
      if (index < this.formData.fields.length - 1) {
        const field = this.formData.fields[index];
        this.formData.fields.splice(index, 1);
        this.formData.fields.splice(index + 1, 0, field);
      }
    },
    addOption(field) {
      field.options.push('New Option');
    },
    removeOption(field, index) {
      field.options.splice(index, 1);
    },
    async saveForm() {
      try {
        const url = this.isEditing ? `/api/forms/${this.id}` : '/api/forms';
        const method = this.isEditing ? 'put' : 'post';
        
        // Prepare the data for API
        const formData = {
          title: this.formData.title,
          fields: this.formData.fields.map(field => ({
            label: field.label,
            type: field.type,
            required: field.required,
            options: field.options || null
          }))
        };
        
        const response = await axios[method](url, formData);
        
        if (!this.isEditing) {
          this.$router.push('/forms');
        }
        
        alert('Form saved successfully!');
      } catch (error) {
        console.error('Error saving form:', error);
        
        if (error.response) {
          // Server responded with error status
          console.error('Response data:', error.response.data);
          alert('Error saving form: ' + (error.response.data.message || 'Please check the console for details.'));
        } else if (error.request) {
          // Request was made but no response received
          alert('Network error. Please check if your backend server is running.');
        } else {
          // Something else happened
          alert('Error saving form. Please check the console for details.');
        }
      }
    }
  }
};
</script>

<style>
.field-item {
  background: #f8f9fa;
}
</style>