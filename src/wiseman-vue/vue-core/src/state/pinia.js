import {
    useLayoutStore
} from "./pinia/layout";
import {
    useAuthStore
} from "./pinia/auth";
import {
    useUserStore
} from "./pinia/user";
import {
    useGroupStore
} from "./pinia/group";
import {
    useGroupUserStore
} from "./pinia/groupUser";
import {
    useActivityStore
} from "./pinia/activity";
import {
    useVotingStore
} from "./pinia/voting";
import {
    useArchiveStore
} from "./pinia/archive";
import {
    useMemoStore
} from "./pinia/memo";
import {
    useEnrollmentStore
} from "./pinia/enrollment";
import {
    useUserVotingStore
} from "./pinia/userVoting";
import {
    createPinia
} from "pinia";

const pinia = createPinia();
export default pinia;

export {
    useLayoutStore,
    useAuthStore,
    useUserStore,
    useGroupStore,
    useGroupUserStore,
    useActivityStore,
    useVotingStore,
    useArchiveStore,
    useMemoStore,
    useEnrollmentStore,
    useUserVotingStore
};