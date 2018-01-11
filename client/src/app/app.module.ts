import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RoutingModule } from './routing.module';
import { MatButtonModule } from '@angular/material';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { CreateContestComponent } from './create_contest/create_contest.component';
import { ContestLiveComponent } from './contest/contest_live/contest_live.component';
import { ContestJudgeComponent } from './contest/contest_judge/contest_judge.component';
import { ContestAdminComponent } from './contest/contest_admin/contest_admin.component';

import { BaseService } from './services/model_services/base.service';
import { StandingService } from './services/model_services/standing.service';


@NgModule({
  declarations: [
    AppComponent,
    CreateContestComponent,
    ContestLiveComponent,
    ContestJudgeComponent,
    ContestAdminComponent
  ],
  imports: [
    BrowserModule,
    NoopAnimationsModule,
    MatButtonModule,
    RoutingModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [
    BaseService,
    StandingService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
