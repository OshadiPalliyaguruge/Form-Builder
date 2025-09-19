<template>
  <div>
    <h1>Form Builder</h1>
    <p class="lead">Manage your forms</p>
    
    <div class="row mt-5">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <h5 class="card-title">Forms</h5>
            <p class="card-text">Create and manage your forms</p>
            <router-link to="/forms" class="btn btn-primary">Manage Forms</router-link>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <h5 class="card-title">Create New</h5>
            <p class="card-text">Build a new form from scratch</p>
            <router-link to="/create" class="btn btn-success">Create Form</router-link>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <h5 class="card-title">Submissions</h5>
            <p class="card-text">View form submissions</p>
            <router-link to="/submissions" class="btn btn-info">View Submissions</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard'
}
</script>