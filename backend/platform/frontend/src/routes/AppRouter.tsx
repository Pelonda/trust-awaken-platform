import { Route, Routes } from 'react-router-dom'

import DashboardLayout from '../layouts/DashboardLayout'

import CreateProgramPage from '../pages/CreateProgramPage'
import DashboardPage from '../pages/DashboardPage'
import EditProgramPage from '../pages/EditProgramPage'
import LoginPage from '../pages/LoginPage'
import ParticipantsPage from '../pages/ParticipantsPage'
import ProgramsPage from '../pages/ProgramsPage'
import SessionsPage from '../pages/SessionsPage'
import AttendancePage from '../pages/AttendancePage'
import CredentialsPage from '../pages/CredentialsPage'

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
          path="/programs/create"
          element={<CreateProgramPage />}
        />

        <Route
          path="/programs/:uuid/edit"
          element={<EditProgramPage />}
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
    </Routes>
  )
}