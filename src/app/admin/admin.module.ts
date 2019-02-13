import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule} from '@angular/forms';
import { HttpModule } from '@angular/http';
import { routing } from './admin.routing';

import { SwitchComponent } from 'angular2-bootstrap-switch/components';

import { DashboardComponent } from './dashboard/dashboard.component';

@NgModule({
  imports: [
      CommonModule,
      FormsModule,
      ReactiveFormsModule,
      HttpModule,
      routing
  ],
  declarations: [
      DashboardComponent,
      // SwitchComponent
  ]
})

export class AdminModule { }
