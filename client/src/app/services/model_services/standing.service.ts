import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Standing } from '../../models/standing';

@Injectable()
export class StandingService {
    constructor(private baseService: BaseService) { }

    getContestStandings() {
        return this.baseService.get('');
    }

    getJudgeStandings() {
        return this.baseService.get('judge');
    }
}