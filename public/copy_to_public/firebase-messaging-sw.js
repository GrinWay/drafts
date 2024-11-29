import { initializeApp } from "firebase/app";
import { getMessaging } from "firebase/messaging/sw";

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
const firebaseApp = initializeApp(JSON.parse(atob('eyJhcGlLZXkiOiAiQUl6YVN5RGFKOVYxRkdUaEVsZGRjN19uSlgxcS1MUVpEV2JiU09nIiwiYXV0aERvbWFpbiI6ICJ3b29kZW5hbGV4ZmNtcHVzaC5maXJlYmFzZWFwcC5jb20iLCJwcm9qZWN0SWQiOiAid29vZGVuYWxleGZjbXB1c2giLCJzdG9yYWdlQnVja2V0IjogIndvb2RlbmFsZXhmY21wdXNoLmZpcmViYXNlc3RvcmFnZS5hcHAiLCJtZXNzYWdpbmdTZW5kZXJJZCI6ICIxMDUxMzQ2Njc0NDgxIiwiYXBwSWQiOiAiMToxMDUxMzQ2Njc0NDgxOndlYjo4OGI3ZDg3YWQ1OTM2YjdkMWYwNjEwIn0=')));

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = getMessaging(firebaseApp);

