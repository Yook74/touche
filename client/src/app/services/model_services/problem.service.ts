import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Problem } from '../../models/problem';

const mockProblems: Problem[] = [
    {
        id: 1,
        name: 'Problem the First',
        location: 'oneproblem',
        note: 'F#'
    },
    {
        id: 2,
        name: 'Problem the Second',
        location: 'twoproblem',
        note: 'A noteworthy note'
    },
    {
        id: 3,
        name: 'Problem the Third',
        location: 'threeproblem',
        note: 'A notable note'
    }
]

@Injectable()
export class ProblemService {
    constructor(private baseService: BaseService) { }

    getMockData() {
        return mockProblems;
    }

    getProblems() {
        return this.baseService.get('problems.php');
    }

    createProblem(problem: Problem) {
        return this.baseService.post('', problem);
    }

    updateProblem(problem: Problem) {
        return this.baseService.put('', problem);
    }

    deleteProblem(problemId: number) {
        return this.baseService.delete('');
    }
}