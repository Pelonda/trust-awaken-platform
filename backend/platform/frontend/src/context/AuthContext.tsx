import {
  createContext,
  useContext,
  useState,
  type ReactNode,
} from 'react'

interface User {
  id: number
  name: string
  email: string
  roles: string[]
  permissions: string[]
}

interface AuthContextType {
  user: User | null
  token: string | null

  login: (
    user: User,
    token: string,
  ) => void

  logout: () => void

  hasRole: (role: string) => boolean

  hasPermission: (
    permission: string,
  ) => boolean
}

const AuthContext =
  createContext<AuthContextType | null>(null)

export function AuthProvider({
  children,
}: {
  children: ReactNode
}) {

  const [user, setUser] = useState<User | null>(() => {

    const stored =
      localStorage.getItem('awaken_user')

    return stored
      ? JSON.parse(stored)
      : null

  })

  const [token, setToken] = useState<string | null>(() =>
    localStorage.getItem('awaken_token')
  )

  function login(
    user: User,
    token: string,
  ) {

    setUser(user)

    setToken(token)

    localStorage.setItem(
      'awaken_user',
      JSON.stringify(user),
    )

    localStorage.setItem(
      'awaken_token',
      token,
    )

  }

  function logout() {

    setUser(null)

    setToken(null)

    localStorage.removeItem('awaken_user')

    localStorage.removeItem('awaken_token')

  }

  function hasRole(role: string): boolean {

    return (
      user?.roles?.includes(role) ?? false
    )

  }

  function hasPermission(
    permission: string,
  ): boolean {

    return (
      user?.permissions?.includes(permission)
      ?? false
    )

  }

  return (

    <AuthContext.Provider
      value={{
        user,
        token,
        login,
        logout,
        hasRole,
        hasPermission,
      }}
    >

      {children}

    </AuthContext.Provider>

  )

}

export function useAuth() {

  const context =
    useContext(AuthContext)

  if (!context) {

    throw new Error(
      'useAuth must be used inside AuthProvider'
    )

  }

  return context

}