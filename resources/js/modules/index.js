/**
 * Intégration des modules DTN et RPM dans l'application FIT
 * Point d'entrée pour l'enregistrement des modules
 */

import { registerDTNRoutes, setUserPermissions as setDTNPermissions } from './DTNManager/router/dtn.routes'
import { registerRPMRoutes, setUserPermissions as setRPMPermissions } from './RPM/router/rpm.routes'

/**
 * Classe principale pour la gestion des modules
 */
class ModuleManager {
  constructor() {
    this.modules = {
      dtn: {
        name: 'DTN Manager',
        description: 'Direction Technique Nationale - Gestion des Équipes Nationales',
        icon: '🏆',
        routes: [],
        permissions: []
      },
      rpm: {
        name: 'RPM',
        description: 'Régulation & Préparation Matchs',
        icon: '⚽',
        routes: [],
        permissions: []
      }
    }
    this.isInitialized = false
  }

  /**
   * Initialiser les modules
   */
  async initialize(router, userPermissions = []) {
    if (this.isInitialized) {
      console.warn('ModuleManager déjà initialisé')
      return
    }

    try {
      // Enregistrer les routes DTN
      registerDTNRoutes(router)
      setDTNPermissions(userPermissions)

      // Enregistrer les routes RPM
      registerRPMRoutes(router)
      setRPMPermissions(userPermissions)

      // Mettre à jour les permissions des modules
      this.updateModulePermissions(userPermissions)

      this.isInitialized = true
      console.log('Modules DTN et RPM initialisés avec succès')
    } catch (error) {
      console.error('Erreur lors de l\'initialisation des modules:', error)
      throw error
    }
  }

  /**
   * Mettre à jour les permissions des modules
   */
  updateModulePermissions(userPermissions) {
    // Permissions DTN
    const dtnPermissions = [
      'dtn_view', 'dtn_teams_view', 'dtn_teams_create', 'dtn_teams_edit',
      'dtn_selections_view', 'dtn_selections_create', 'dtn_selections_edit', 'dtn_selections_manage',
      'dtn_expats_view', 'dtn_medical_view', 'dtn_planning_view', 'dtn_reports_view',
      'dtn_settings', 'dtn_admin'
    ]

    this.modules.dtn.permissions = dtnPermissions.filter(perm => 
      userPermissions.includes(perm)
    )

    // Permissions RPM
    const rpmPermissions = [
      'rpm_view', 'rpm_calendar_view', 'rpm_sessions_view', 'rpm_sessions_create', 'rpm_sessions_edit',
      'rpm_matches_view', 'rpm_matches_create', 'rpm_matches_edit', 'rpm_load_view',
      'rpm_attendance_view', 'rpm_reports_view', 'rpm_sync', 'rpm_settings', 'rpm_admin'
    ]

    this.modules.rpm.permissions = rpmPermissions.filter(perm => 
      userPermissions.includes(perm)
    )
  }

  /**
   * Obtenir les modules accessibles selon les permissions
   */
  getAccessibleModules() {
    return Object.keys(this.modules).filter(moduleKey => {
      const module = this.modules[moduleKey]
      return module.permissions.length > 0 || userPermissions.includes(`${moduleKey}_admin`)
    }).map(moduleKey => ({
      key: moduleKey,
      ...this.modules[moduleKey]
    }))
  }

  /**
   * Vérifier si un module est accessible
   */
  isModuleAccessible(moduleKey) {
    const module = this.modules[moduleKey]
    if (!module) return false
    
    return module.permissions.length > 0 || userPermissions.includes(`${moduleKey}_admin`)
  }

  /**
   * Obtenir les informations d'un module
   */
  getModuleInfo(moduleKey) {
    return this.modules[moduleKey] || null
  }

  /**
   * Obtenir tous les modules
   */
  getAllModules() {
    return this.modules
  }

  /**
   * Recharger les modules
   */
  async reload(router, userPermissions = []) {
    this.isInitialized = false
    await this.initialize(router, userPermissions)
  }
}

// Instance singleton
const moduleManager = new ModuleManager()

/**
 * Fonction d'initialisation pour l'application FIT
 */
export const initializeModules = async (router, userPermissions = []) => {
  return await moduleManager.initialize(router, userPermissions)
}

/**
 * Fonction pour obtenir les modules accessibles
 */
export const getAccessibleModules = () => {
  return moduleManager.getAccessibleModules()
}

/**
 * Fonction pour vérifier l'accessibilité d'un module
 */
export const isModuleAccessible = (moduleKey) => {
  return moduleManager.isModuleAccessible(moduleKey)
}

/**
 * Fonction pour obtenir les informations d'un module
 */
export const getModuleInfo = (moduleKey) => {
  return moduleManager.getModuleInfo(moduleKey)
}

/**
 * Fonction pour recharger les modules
 */
export const reloadModules = async (router, userPermissions = []) => {
  return await moduleManager.reload(router, userPermissions)
}

/**
 * Navigation items pour l'application principale
 */
export const getModuleNavigationItems = () => {
  const accessibleModules = getAccessibleModules()
  
  return accessibleModules.map(module => ({
    name: module.name,
    description: module.description,
    icon: module.icon,
    route: `${module.key}.dashboard`,
    module: module.key,
    permissions: module.permissions
  }))
}

/**
 * Configuration des modules pour l'application
 */
export const moduleConfig = {
  dtn: {
    name: 'DTN Manager',
    description: 'Direction Technique Nationale',
    icon: '🏆',
    color: 'blue',
    features: [
      'Gestion des équipes nationales',
      'Sélections internationales',
      'Suivi des joueurs expatriés',
      'Interface médicale clubs',
      'Planification technique'
    ]
  },
  rpm: {
    name: 'RPM',
    description: 'Régulation & Préparation Matchs',
    icon: '⚽',
    color: 'green',
    features: [
      'Calendrier d\'entraînement',
      'Gestion des sessions',
      'Préparation des matchs',
      'Monitoring charge joueurs',
      'Suivi des présences'
    ]
  }
}

/**
 * Fonction pour obtenir la configuration d'un module
 */
export const getModuleConfig = (moduleKey) => {
  return moduleConfig[moduleKey] || null
}

/**
 * Fonction pour obtenir toutes les configurations
 */
export const getAllModuleConfigs = () => {
  return moduleConfig
}

export default moduleManager 