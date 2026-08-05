import { Route, Routes } from 'react-router-dom'

import DashboardLayout from '../components/layouts/DashboardLayout'

import LoginPage from '../pages/LoginPage'
import DashboardPage from '../pages/DashboardPage'

import { ProgramsPage } from '../features/programs'
import { ParticipantsPage } from '../features/participants'
import { SessionsPage } from '../features/sessions'
import { AttendancePage } from '../features/attendance'
import { CredentialsPage } from '../features/credentials'
import { VerificationPage } from '../features/verification'

export default function AppRouter() {
  return (
    <Routes>

      <Route
        path="/"
        element={<LoginPage />}
      />

      <Route element={<DashboardLayout />}>

        <Route
          path="/dashboard"
          element={<DashboardPage />}
        />

        <Route
          path="/programs"
          element={<ProgramsPage />}
        />

        <Route
          path="/participants"
          element={<ParticipantsPage />}
        />

        <Route
          path="/sessions"
          element={<SessionsPage />}
        />

        <Route
          path="/attendance"
          element={<AttendancePage />}
        />

        <Route
          path="/credentials"
          element={<CredentialsPage />}
        />

      </Route>

      <Route
        path="/verify"
        element={<VerificationPage />}
      />

    </Routes>
  )
}