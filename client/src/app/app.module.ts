import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RoutingModule } from './routing.module';
import { MatButtonModule, MatSidenavModule } from '@angular/material';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { CreateContestComponent } from './create_contest/create_contest.component';
import { ContestComponent } from './contest/contest.component';
import { ContestLiveComponent } from './contest/contest_live/contest_live.component';
import { LiveSideNavComponent } from './contest/contest_live/sidenav/sidenav.component';
import { ContestJudgeComponent } from './contest/contest_judge/contest_judge.component';
import { JudgeSideNavComponent } from './contest/contest_judge/sidenav/sidenav.component';
import { ContestAdminComponent } from './contest/contest_admin/contest_admin.component';
import { AdminSideNavComponent } from './contest/contest_admin/sidenav/sidenav.component';

import { BaseService } from './services/model_services/base.service';
import { CategoryService } from './services/model_services/category.service';
import { ClarificationService } from './services/model_services/clarification.service';
import { ContestInfoService } from './services/model_services/contest_info.service';
import { ProblemService } from './services/model_services/problem.service';
import { ResponseService } from './services/model_services/response.service';
import { StandingService } from './services/model_services/standing.service';
import { SubmissionService } from './services/model_services/submission.service';
import { TeamService } from './services/model_services/team.service';


@NgModule({
  declarations: [
    AppComponent,
    CreateContestComponent,
    ContestComponent,
    ContestLiveComponent,
    LiveSideNavComponent,
    ContestJudgeComponent,
    JudgeSideNavComponent,
    ContestAdminComponent,
    AdminSideNavComponent
  ],
  imports: [
    BrowserModule,
    NoopAnimationsModule,
    MatButtonModule,
    MatSidenavModule,
    RoutingModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [
    BaseService,
    CategoryService,
    ClarificationService,
    ContestInfoService,
    ProblemService,
    ResponseService,
    StandingService,
    SubmissionService,
    TeamService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
