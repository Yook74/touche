import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Submission } from '../../models/submission';

@Injectable()
export class SubmissionService {
    constructor(private baseService: BaseService) { }

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