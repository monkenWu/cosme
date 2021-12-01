/* eslint-disable camelcase */
/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
import { MutationTree } from 'vuex';
import { State } from './types';

export interface Mutations<S = State> {
  setTryHistoryList(state: S, newMakeupPostList: any): void;
}

const mutations: MutationTree<State> & Mutations = {
  setTryHistoryList(state, newTryHistoryList) {
    state.tryHistoryList = newTryHistoryList;
  },
};

export default mutations;
