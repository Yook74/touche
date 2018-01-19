import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Submission } from '../../models/submission';

const mockSubmissions: Submission[] = [
    {
        id: 1,
        teamId: 1,
        problemId: 1,
        timestamp: '02:06:51',
        attempts: 4,
        sourceFile: 'source',
        responseId: 1,
        autoResponseId: 1,
        viewed: 1
    },
    {
        id: 2,
        teamId: 2,
        problemId: 2,
        timestamp: '12:44:51',
        attempts: 4,
        sourceFile: 'source',
        responseId: 1,
        autoResponseId: 1,
        viewed: 1
    },
    {
        id: 3,
        teamId: 3,
        problemId: 3,
        timestamp: '12:00:51',
        attempts: 2,
        sourceFile: 'source',
        responseId: 1,
        autoResponseId: 1,
        viewed: 1
    }
]

@Injectable()
export class SubmissionService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockSubmissions;
    }

    getSubmissions() {
        return this.baseService.get('');
    }

    createSubmission(submission: Submission) {
        return this.baseService.post('', submission);
    }

    updateSubmission(submission: Submission) {
        return this.baseService.put('', submission);
    }
}
