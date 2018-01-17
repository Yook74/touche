import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { ContestInfo } from '../../models/contest_info';

const mockData: ContestInfo = {
    host: 'Taylor University',
    name: 'Fake Contest',
    startDate: '2019-01-08',
    startTime: '07:39',
    freezeDelay: 2000,
    contestEndDelay: 3000,
    ignoreStandardError: true,
    hasStarted: false,
    judgeUsername: 'judge',
    judgePassword: 'password',
    showTeams: true,
    baseDirectory: '/home'
};

@Injectable()
export class ContestInfoService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockData;
    }

    getContestInfo() {
        return this.baseService.get('');
    }

    updateContestInfo(contestInfo: ContestInfo) {
        return this.baseService.put('', contestInfo);
    }
}
